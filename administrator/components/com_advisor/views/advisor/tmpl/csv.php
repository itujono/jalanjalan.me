<?php
/*
 * @component %%COMPONENTNAME%% 
 * @author Sergiu Tudor
 * @license GPL 3.0
 * This program is free software: you can redistribute it and/or modify it under the terms of the 
 * GNU General Public License as published by the Free Software Foundation, either version 3 of the License, 
 * or (at your option) any later version.
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 * See the GNU General Public License for more details.
 * See <http://www.gnu.org/licenses/>.
 * More info www.moonsoft.es gestion@moonsoft.es 
 */


class csv{
    private $output = '';
    private $rows = array();
    private $current_row = array();
    private $current_row_index = 0;
    


    private $enclosed_by = "\"";
    private $escaped_by = "\\";
    private $separated_by = ";";
    private $terminated_by = "\n";
    
    /**
     * set the character used to enclose values
     *
     * @param string $char
     */
    public function set_enclose_char($char){
        $this->enclosed_by = $char;
    }
    
    /**
     * set the character used to escape special chars
     *
     * @param string $char
     */
    public function set_escape_char($char){
        $this->escaped_by = $char;
    }
    
    /**
     * set the character used to separate values
     *
     * @param string $char
     */
    public function set_separate_char($char){
        $this->separated_by = $char;
    }
    
    /**
     * set the character used to terminate a row
     *
     * @param string $char
     */
    public function set_terminate_char($char){
        $this->terminated_by = $char;
    }
    
    /**
     * Loads the CSV data
     *
     * @param array $data ['row1':['val1','val2'],'ro2':['val3','val4']...]
     */
    public function load_data($data){
        if (is_array($data)) {
            foreach ($data as $row) {
                $this->new_row($row);
            }
        }
    }
    
    /**
     * creates a new row in the CSV
     *
     * @param array $row_data the data to be added to the row
     */
    public function new_row($row_data=null){
        $this->rows[$this->current_row_index] = array();
        $this->current_row = &$this->rows[$this->current_row_index];
        
        $this->current_row_index++;
        
        if (is_array($row_data)) {
            $this->current_row = $row_data;
        }
    }
    
    /**
     * Adds a value to the current row
     *
     * @param mixed $value
     */
    public function add_value($value){
        $this->current_row[] = $value;
    }
    
    /**
     * Encodes the loaded data into a CSV string
     *
     * @return string the generated CSV
     */
    public function encode_data(){
        $encoded_rows = array();
        foreach ($this->rows as $row) {
            foreach ($row as $key=>$value) {
                // escape char first
                $value = str_replace($this->escaped_by, "{$this->escaped_by}{$this->escaped_by}", $value);
                // enclose char
                $value = str_replace($this->enclosed_by, "{$this->escaped_by}{$this->enclosed_by}", $value);
                // terminate char
                $value = str_replace($this->terminated_by, "{$this->escaped_by}{$this->terminated_by}", $value);
                
                $row[$key] = $value;
            }
            $encoded_rows[] = $this->enclosed_by.

                implode("$this->enclosed_by$this->separated_by$this->enclosed_by", $row).

                $this->enclosed_by;
        }
        
        return $this->output = implode($this->terminated_by, $encoded_rows);
    }
    
    /**
     * Sends the file for downlows
     *
     * @param string $filename filename without .csv extension
     */
    function send_file($filename='data'){
        $this->encode_data();
        
        header ("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
        header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header ("Cache-Control: no-cache, must-revalidate");
        header ("Pragma: no-cache");
        header ("Content-type: application/CSV");
        header ("Content-Disposition: attachment; filename=$filename.csv");
        header ("Content-Description: PHP Generated CSV Data");
        print $this->output;
    }
    
    public function getStringOutput(){
    	$this->encode_data();
    	return $this->output;
    }
}

?> 