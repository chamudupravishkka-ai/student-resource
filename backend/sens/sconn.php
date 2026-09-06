<?php
$host = "localhost"; 
$user = "root";
$pass = "root";
$db   = "stresource";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function getData(mysqli $conn, string $tabname, array $colnames, array $params = [], string $where = "", string $orderBy = "", int $limit = 0): array {
    // 1. Sanitize/wrap table and column names to prevent identifier injection
    $escapedTabname = "`" . str_replace("`", "``", $tabname) . "`";
    
    $escapedCols = array_map(function($col) {
        // Allow wildcards like COUNT(*) or simple column names / aliases
        if ($col === '*') return '*';
        return "`" . str_replace("`", "``", trim($col)) . "`";
    }, $colnames);
    
    $sql = "SELECT " . implode(", ", $escapedCols) . " FROM " . $escapedTabname;
    
    if (!empty($where)) {
        $sql .= " WHERE " . $where;
    }

    // Add ORDER BY clause if provided
    if (!empty($orderBy)) {
        $sql .= " ORDER BY " . $orderBy;
    }

    // Add LIMIT clause if greater than 0
    if ($limit > 0) {
        $sql .= " LIMIT " . (int)$limit;
    }
    
    // 2. Prepare the statement
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    // 3. Dynamically bind parameters if provided
    if (!empty($params)) {
        $types = "";
        $bindValues = [];
        
        foreach ($params as $value) {
            if (is_int($value)) {
                $types .= "i";
            } elseif (is_float($value)) {
                $types .= "d";
            } elseif (is_string($value)) {
                $types .= "s";
            } else {
                $types .= "b";
            }
            $bindValues[] = $value;
        }
        
        $stmt->bind_param($types, ...$bindValues);
    }
    
    // 4. Execute and fetch results safely
    $stmt->execute();
    $result = $stmt->get_result();
    
    $rows = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    
    $stmt->close();
    return $rows; 
}
function setData(mysqli $conn, string $tabname, array $data){
    if (empty($data)) {
        throw new InvalidArgumentException("Data array cannot be empty for an INSERT operation.");
    }

    // 1. Sanitize/wrap table name to prevent identifier injection
    $escapedTabname = "`" . str_replace("`", "``", $tabname) . "`";

    $columns = [];
    $placeholders = [];
    $types = "";
    $bindValues = [];

    // 2. Process columns, values, and types
    foreach ($data as $col => $value) {
        // Sanitize column name
        $columns[] = "`" . str_replace("`", "``", trim($col)) . "`";
        $placeholders[] = "?";

        // Determine type for bind_param
        if (is_int($value)) {$types .= "i";
        } elseif (is_float($value)) {$types .= "d";
        } elseif (is_string($value)) {$types .= "s";
        } else {
            // Handles blobs, nulls, or resources if necessary
            $types .= "b";
        }

        $bindValues[] =$value;
    }

    // 3. Construct the SQL INSERT statement
    $sql = "INSERT INTO " . $escapedTabname . " (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $placeholders) . ")";

    // 4. Prepare the statement
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    // 5. Dynamically bind parameters
    $stmt->bind_param($types, ...$bindValues);

    // 6. Execute the statement
    $success =$stmt->execute();
    
    if (!$success) {
        $error =$stmt->error;
        $stmt->close();
        throw new Exception("Execute failed: " . $error);
    }

    // 7. Capture insert ID or return success status
    $insertId =$conn->insert_id;
    
    $stmt->close();

    // Return the auto-increment ID if generated, otherwise return true
    return $insertId > 0 ?$insertId : true;
}
function getDataWithJoins(
    mysqli $conn, 
    string $fromTable, 
    array $colnames, 
    array $joins = [], 
    array $params = [], 
    string $where = "", 
    string $orderBy = "", 
    int $limit = 0
): array {
    // 1. Sanitize the main table name
    $escapedTabname = "`" . str_replace("`", "``", $fromTable) . "`";
    
    // 2. Sanitize column names/aliases
    $escapedCols = array_map(function($col) {
        if ($col === '*') return '*';
        // Allow expressions or dot notation like `users`.`name` or `u.name AS username`
        return $col; 
    }, $colnames);
    
    $sql = "SELECT " . implode(", ", $escapedCols) . " FROM " . $escapedTabname;
    
    // 3. Append JOIN clauses if provided
    // Example format for $joins: ["LEFT JOIN categories c ON documents.cat_id = c.ID"]
    if (!empty($joins)) {
        foreach ($joins as $join) {
            $sql .= " " . $join;
        }
    }
    
    // 4. Append WHERE clause
    if (!empty($where)) {
        $sql .= " WHERE " . $where;
    }

    // 5. Append ORDER BY clause
    if (!empty($orderBy)) {
        $sql .= " ORDER BY " . $orderBy;
    }

    // 6. Append LIMIT clause
    if ($limit > 0) {
        $sql .= " LIMIT " . (int)$limit;
    }
    
    // 7. Prepare the statement
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    // 8. Dynamically bind parameters
    if (!empty($params)) {
        $types = "";
        $bindValues = [];
        
        foreach ($params as $value) {
            if (is_int($value)) {
                $types .= "i";
            } elseif (is_float($value)) {
                $types .= "d";
            } elseif (is_string($value)) {
                $types .= "s";
            } else {
                $types .= "b";
            }
            $bindValues[] = $value;
        }
        
        $stmt->bind_param($types, ...$bindValues);
    }
    
    // 9. Execute and fetch results safely
    $stmt->execute();
    $result = $stmt->get_result();
    
    $rows = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    
    $stmt->close();
    return $rows; 
}
?>