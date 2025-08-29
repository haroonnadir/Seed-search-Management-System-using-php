<?php
// Include database connection
include 'db_connection.php';

// Initialize search variables
$searchCategory = "";
$searchTitle = "";
$searchResults = [];

// Process search if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "GET" && (isset($_GET['searchCategory']) || isset($_GET['searchTitle']))) {
    $searchCategory = $_GET['searchCategory'];
    $searchTitle = $_GET['searchTitle'];
    
    // Build SQL query based on search criteria
    $sql = "SELECT * FROM seeds WHERE 1=1";
    
    if (!empty($searchCategory)) {
        $sql .= " AND category = '" . $conn->real_escape_string($searchCategory) . "'";
    }
    
    if (!empty($searchTitle)) {
        $sql .= " AND title LIKE '%" . $conn->real_escape_string($searchTitle) . "%'";
    }
    
    $sql .= " ORDER BY title";
    
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $searchResults[] = $row;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seed Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: url('https://images.unsplash.com/photo-1523348837708-15d4a09cfac2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            line-height: 1.6;
            position: relative;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.85);
            z-index: -1;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            text-align: center;
            padding: 20px 0;
            margin-bottom: 30px;
            background-color: rgba(76, 175, 80, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            color: white;
        }
        
        h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .description {
            font-size: 1.1rem;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .main-content {
            display: flex;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .section {
            flex: 1;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .section-title {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #4CAF50;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }
        
        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        
        input:focus, select:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
        }
        
        .buttons {
            display: flex;
            gap: 15px;
        }
        
        button {
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .submit-btn {
            background-color: #4CAF50;
            color: white;
        }
        
        .submit-btn:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }
        
        .reset-btn {
            background-color: #f44336;
            color: white;
        }
        
        .reset-btn:hover {
            background-color: #e53935;
            transform: translateY(-2px);
        }
        
        .search-btn {
            background-color: #2196F3;
            color: white;
        }
        
        .search-btn:hover {
            background-color: #0b7dda;
            transform: translateY(-2px);
        }
        
        .search-results {
            margin-top: 20px;
        }
        
        .seed-card {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }
        
        .seed-card h3 {
            color: #2E7D32;
            margin-bottom: 10px;
        }
        
        .seed-card p {
            margin-bottom: 8px;
        }
        
        .seed-details {
            display: none;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        
        .detail-item {
            margin-bottom: 10px;
            display: flex;
        }
        
        .detail-label {
            font-weight: 600;
            min-width: 150px;
            color: #555;
        }
        
        footer {
            text-align: center;
            padding: 20px;
            margin-top: 40px;
            background-color: rgba(76, 175, 80, 0.9);
            border-radius: 10px;
            color: white;
        }
        
        .message {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: center;
            font-weight: 600;
        }
        
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        @media (max-width: 768px) {
            .main-content {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-seedling"></i> Seed Management System</h1>
            <p class="description">Manage your seed inventory with ease. Add new seeds and search existing ones.</p>
        </header>
        
        <div class="main-content">
            <div class="section">
                <h2 class="section-title">Add New Seed</h2>
                <?php
                // Display success/error messages
                if (isset($_GET['message'])) {
                    $message = $_GET['message'];
                    $class = (isset($_GET['success']) && $_GET['success'] == 1) ? 'success' : 'error';
                    echo "<div class='message $class'>$message</div>";
                }
                ?>
                <form id="seedForm" action="process_seed.php" method="POST">
                    <div class="form-group">
                        <label for="seedId">Seed ID:</label>
                        <input type="text" id="seedId" name="seedId" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="category">Category:</label>
                        <select id="category" name="category" required>
                            <option value="">Select Category</option>
                            <option value="Vegetables">Vegetables</option>
                            <option value="Fruits">Fruits</option>
                            <option value="Flowers">Flowers</option>
                            <option value="Herbs">Herbs</option>
                            <option value="Grains">Grains</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="color">Color:</label>
                        <input type="text" id="color" name="color" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="costPerKg">Cost Per Kg ($):</label>
                        <input type="number" id="costPerKg" name="costPerKg" step="0.01" required>
                    </div>
                    
                    <div class="buttons">
                        <button type="submit" class="submit-btn"><i class="fas fa-plus-circle"></i> Submit</button>
                        <button type="reset" class="reset-btn"><i class="fas fa-redo"></i> Reset</button>
                    </div>
                </form>
            </div>
            
            <div class="section">
                <h2 class="section-title">Search Seeds</h2>
                <form method="GET" action="">
                    <div class="form-group">
                        <label for="searchCategory">Category:</label>
                        <select id="searchCategory" name="searchCategory">
                            <option value="">All Categories</option>
                            <option value="Vegetables" <?php if($searchCategory == 'Vegetables') echo 'selected'; ?>>Vegetables</option>
                            <option value="Fruits" <?php if($searchCategory == 'Fruits') echo 'selected'; ?>>Fruits</option>
                            <option value="Flowers" <?php if($searchCategory == 'Flowers') echo 'selected'; ?>>Flowers</option>
                            <option value="Herbs" <?php if($searchCategory == 'Herbs') echo 'selected'; ?>>Herbs</option>
                            <option value="Grains" <?php if($searchCategory == 'Grains') echo 'selected'; ?>>Grains</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="searchTitle">Title:</label>
                        <input type="text" id="searchTitle" name="searchTitle" placeholder="Enter seed title" value="<?php echo htmlspecialchars($searchTitle); ?>">
                    </div>
                    
                    <button type="submit" class="search-btn"><i class="fas fa-search"></i> Search</button>
                </form>
                
                <div class="search-results">
                    <?php
                    if (!empty($searchResults)) {
                        foreach ($searchResults as $seed) {
                            echo "<div class='seed-card'>";
                            echo "<h3>" . htmlspecialchars($seed['title']) . "</h3>";
                            echo "<p><strong>Category:</strong> " . htmlspecialchars($seed['category']) . "</p>";
                            echo "<p><strong>Color:</strong> " . htmlspecialchars($seed['color']) . "</p>";
                            echo "<p><strong>Cost Per Kg:</strong> $" . number_format($seed['costPerKg'], 2) . "</p>";
                            echo "<p><strong>Seed ID:</strong> " . htmlspecialchars($seed['seedId']) . "</p>";
                            echo "</div>";
                        }
                    } elseif (isset($_GET['searchCategory']) || isset($_GET['searchTitle'])) {
                        echo "<p>No seeds found matching your criteria.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
        
        <footer>
            <p>&copy; 2025 Seed Management System | Designed for virtual university final year projects</p>
        </footer>
    </div>
</body>
</html>