<?php

namespace WhatsAppChatChitavo\Inc;

defined( 'ABSPATH' ) || exit; // Prevent direct access

use wpdb;

if ( !class_exists( 'WACCHelper' ) ) {
    class WACCHelper extends wpdb {

        public function __construct()
        {
            parent::__construct(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
        }

        public function insert_multiple( $table, $data, $format = null )
        {
            $this->insert_id = 0;

            $formats = array();
            $values = array();

            foreach ( $data as $index => $row )
            {
                $row = $this->process_fields( $table, $row, $format );
                $row_formats = array();

                if ( $row === false || array_keys( $data[$index] ) !== array_keys( $data[0] ))
                {
                    continue;
                }

                foreach( $row as $col => $value )
                {
                    if ( is_null($value['value']) )
                    {
                        $row_formats[] = 'NULL';
                    } else {
                        $row_formats[] = $value['format'];
                    }
                    $values[] = $value['value'];
                }

                $formats[] = '(' . implode( ', ', $row_formats ) . ')';
            }

            $fields  = '`' . implode( '`, `', array_keys( $data[0] ) ) . '`';
            $formats = implode( ', ', $formats );
            $sql = "INSERT INTO `$table` ($fields) VALUES $formats";

            $this->check_current_query = false;
            return $this->query( $this->prepare( $sql, $values ) );
        }

        public function update_multiple( $queries )
        {
            // set array
            $error = array();

            // run update commands
            foreach( $queries as $query )
            {
                $last_error = $this->last_error;
                $this->query( $query );

                // fill array when we have an error
                if( ( empty( $this->result ) || !$this->result ) && !empty( $this->last_error ) && $last_error != $this->last_error )
                {
                    $error[] = $this->last_error." ($query)";
                }
            }

            // when we have an error
            if( $error )
            {
                return $error;
            }
            return false;
        }
    }
}