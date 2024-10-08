<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spaced Repetition Study Management App</title>
    <meta name="description"
          content="A Spaced Repetition App designed to enhance learning and memorization using the spaced repetition technique. Manage study subjects efficiently with features like adding, editing, deleting, searching, exporting, and importing data.">
    <meta name="keywords"
          content="spaced repetition, study management, learning tool, memorization, education, web app, javascript, css, html, responsive design, data export, data import, notifications, google material icons, toastify.js, study aid, learning app, student tool, educational technology">
    <meta name="author" content="Your Name">
    <meta property="og:title" content="Spaced Repetition Study Management App">
    <meta property="og:description"
          content="Enhance your learning and memorization with our Spaced Repetition App. Manage study subjects efficiently with various features.">
    <meta property="og:image" content="path/to/your/image.png">
    <meta property="og:url" content="https://yourgithubpageurl.com">
    <meta property="og:type" content="website">
    <link rel="shortcut icon" href="assets/icons/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
</head>

<body>
<div class="sidebar" id="sidebar">
    <div class="sidebar-toggle" id="sidebarToggle">
        <span class="material-icons">menu</span>
    </div>
    <nav>
        <ul>
            <li><a href="#form-section" class="nav-link"><span class="material-icons">add_circle</span><span
                        class="link-text">Add Subject</span></a></li>
            <li><a href="#subjects-section" class="nav-link"><span class="material-icons">view_list</span><span
                        class="link-text">Subjects</span></a></li>
            <li><a href="#" id="exportBtn"><span class="material-icons">file_download</span><span
                        class="link-text">Export</span></a></li>
            <li><a href="#" id="importBtn"><span class="material-icons">file_upload</span><span
                        class="link-text">Import</span></a></li>
        </ul>
    </nav>
    <div id="stats" class="sidebar-section">
        <h2>Progress Statistics</h2>
        <p>Total Subjects: <span id="totalSubjects">0</span></p>
        <p>Completed Reviews: <span id="completedReviews">0</span></p>
    </div>
</div>
<div class="content">
    <header>
        <h1>Spaced Repetition App</h1>
    </header>
    <main>
        <div class="form-subjects-container">
            <section class="form-section" id="form-section">
                <h2>Add New Subject</h2>
                <form id="subjectForm">
                    <label for="subjectName">Subject Name:</label>
                    <input type="text" id="subjectName" required>

                    <label for="subjectDescription">Description:</label>
                    <textarea id="subjectDescription" required></textarea>

                    <label for="subjectTags">Tags (comma separated):</label>
                    <input type="text" id="subjectTags">

                    <button type="submit"><span class="material-icons">add</span> Add Subject</button>
                </form>
            </section>
            <section class="subjects-section" id="subjects-section">
                <h2>Subjects</h2>
                <input type="text" id="searchBox" placeholder="Search...">
                <div class="subjects-grid">
                    <ul id="subjectsList"></ul>
                </div>
            </section>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Spaced Repetition App</p>
    </footer>
    <button id="scrollToTopBtn"><span class="material-icons">arrow_upward</span></button>
    <input type="file" id="importFile" style="display: none;">
</div>
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="script.js"></script>
</body>

</html>
