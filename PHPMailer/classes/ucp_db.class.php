<?php
/*

	MADE WITH ANANG FEBRI PANGESTU
	LOST JAVA INDONESIA

*/
class Db 
{
    protected static $connection;

    public function connect() 
	{
        if(!isset(self::$connection))
		{
            self::$connection = new mysqli('n59.ultra-h.com', 'server_5675', 'ljrpdb', 'server_5675_ljrpdb');
        }
        if(self::$connection === false)
		{
            return FALSE;
        }
        return self::$connection;
    }

    public function query($query)
	{
        $connection = $this->connect();
        $result = $connection->query($query);
		if($result == TRUE)
		{
			return TRUE;
		}
		else
		{
			return '0';
		}
	}

    public function select($query)
	{
        $rows = array();
		$connection = $this->connect();
        $result = $connection->query($query);
        if($result === false)
		{
            return FALSE;
        }
        while ($row = $result->fetch_assoc())
		{
            $rows[] = $row;
        }
		//$result->free();
        return $rows;
    }
	
	public function delete($table, $column, $value)
	{
		
		if($this->exists($table, $column, $value) == 1)
		{
			$result = $this->query("DELETE FROM $table WHERE $column =".$value);
			return TRUE;
		}
		else
		{
			return '0';
		}
	}
	
	public function	exists($table, $column, $value) 
	{
		$connection = $this->connect();
        $result = $connection->query("SELECT * FROM $table WHERE $column = '".$value."'");
		if($result == TRUE)
		{
			if($result->num_rows == 1) 
			{
				return TRUE;
			}
			else
			{
				return '0';
			}
		}
	}
	
	public function numrows($table)
	{
		$connection = $this->connect();
		$result = $connection->query("SELECT * FROM users");
		if($result == TRUE)
		{
			if($result->num_rows > 0) 
			{
				return $result->num_rows;
			}
			else
			{
				return '0';
			}
		}
	}
	
    public function error()
	{
        $connection = $this->connect();
        return $connection->error;
    }

    public function quote($value)
	{
        $connection = $this->connect();
        return "" . $connection->real_escape_string($value) . "";
    }
    public function close()
    {
        mysqli_close(self::$connection);
    }
}
?>