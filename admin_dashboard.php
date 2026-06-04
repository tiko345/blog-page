<!DOCTYPE html>
<html lang="en" data-theme="light">
<?php
session_start();
require_once "./templates/head.php";
require_once "./config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $conn->prepare("SELECT Id, username, email, role FROM users WHERE Id = ?");
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$currentUser = $stmt->get_result()->fetch_assoc();

if (!$currentUser || $currentUser['role'] !== 'admin') {
    header("Location: user_dashboard.php");
    exit;
}

$selectedUserId = isset($_GET['user_id']) ? (int) $_GET['user_id'] : 0;
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['admin_note'])) {
    $selectedUserId = (int) $_POST['user_id'];
    $note = trim($_POST['admin_note']);

    $stmt = $conn->prepare("
        INSERT INTO admin_user_info (user_id, info, updated_by)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE
            info = VALUES(info),
            updated_by = VALUES(updated_by),
            updated_at = CURRENT_TIMESTAMP
    ");
    $stmt->bind_param('isi', $selectedUserId, $note, $currentUser['Id']);
    $stmt->execute();

    $message = "User information saved.";
}

$totalUsers = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$totalArticles = $conn->query("SELECT COUNT(*) AS total FROM articles")->fetch_assoc()['total'];
$publishedArticles = $conn->query("SELECT COUNT(*) AS total FROM articles WHERE status = 'published'")->fetch_assoc()['total'];
$draftArticles = $conn->query("SELECT COUNT(*) AS total FROM articles WHERE status = 'draft'")->fetch_assoc()['total'];

$users = $conn->query("
    SELECT users.Id, users.username, users.email, users.role, COUNT(articles.Id) AS article_count, admin_user_info.info AS admin_note
    FROM users
    LEFT JOIN articles ON articles.user_id = users.Id
    LEFT JOIN admin_user_info ON admin_user_info.user_id = users.Id
    GROUP BY users.Id
    ORDER BY users.username ASC
")->fetch_all(MYSQLI_ASSOC);

if ($selectedUserId === 0 && !empty($users)) {
    $selectedUserId = (int) $users[0]['Id'];
}

$selectedUser = null;
foreach ($users as $user) {
    if ((int) $user['Id'] === $selectedUserId) {
        $selectedUser = $user;
        break;
    }
}


// insert admin note for selected user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_note'])) {
    $userId = (int) $_POST['user_id'];
    $note   = trim($_POST['admin_note']);

    $stmt = $conn->prepare("
        INSERT INTO admin_user_info (user_id, info, updated_by)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE
            info = VALUES(info),
            updated_by = VALUES(updated_by),
            updated_at = CURRENT_TIMESTAMP
    ");
    $stmt->bind_param('isi', $userId, $note, $currentUser['Id']);
    $stmt->execute();

    $message = "Note saved.";
    
    // redirect to avoid resubmit on refresh
    header('Location: admin_dashboard.php?saved=1');
    exit;
}

// show success message after redirect
if (isset($_GET['saved'])) {
    $message = "Note saved successfully.";
}

?>
<body class="dashboard-page">
<?php
    $currentPage = 'admin';
    require_once "./templates/header.php";
?>

<div class="dashboard-container">
    <aside class="dashboard-aside">
        <div class="dashboard-cont">
            <p><?php echo strtoupper(substr($currentUser['username'], 0, 1)); ?></p>
            <span class="author">Admin Panel</span>
        </div>
        <nav>
            <span>Menu</span>
            <ul>
                <li><a class="nav-item active" href="#" data-section="dashboard">Dashboard</a></li>
                <li><a class="nav-item" href="#" data-section="users">Users</a></li>
            </ul> 
        </nav>
    </aside>

    <main class="dashboard-content">
        <section class="section active" id="dashboard">
            <h1>Admin Dashboard</h1>
            <p>Platform overview and user information management.</p>
            <div class="stat-grid">
                <article class="stat-card">
                    <span class="stat-label">Total Users</span>
                    <span class="stat-number"><?php echo $totalUsers; ?></span>
                </article>
                <article class="stat-card">
                    <span class="stat-label">Total Articles</span>
                    <span class="stat-number"><?php echo $totalArticles; ?></span>
                </article>
                <article class="stat-card">
                    <span class="stat-label">Published Articles</span>
                    <span class="stat-number"><?php echo $publishedArticles; ?></span>
                </article>
                <article class="stat-card">
                    <span class="stat-label">Draft Articles</span>
                    <span class="stat-number"><?php echo $draftArticles; ?></span>
                </article>
            </div>
        </section>

        <section class="section" id="users">
            <div class="users-toolbar">
                <input type="text" id="user-search" placeholder="Search users by name or email...">
                <select id="role-filter">
                    <option value="">All</option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>
            <table class="users-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Articles</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody id="users-tbody">
                    <?php foreach ($users as $user): ?>
                        <tr class="user-row-table" data-name="<?php echo strtolower($user['username']); ?>" data-email="<?php echo strtolower($user['email']); ?>" data-role="<?php echo $user['role']; ?>">
                            <td>
                                <div class="user-cell">
                                    <div class="user-avatar">
                                        <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                                    </div>
                                    <span><?php echo htmlspecialchars($user['username']); ?></span>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <span class="status-pill <?php echo $user['role'] === 'admin' ? 'admin' : ''; ?>">
                                    <?php echo htmlspecialchars($user['role']); ?>
                                </span>
                            </td>
                            <td><?php echo $user['article_count']; ?></td>
                            <td>
                                <div class="action-btns">
                                    <button class="btn-note" 
                                            onclick="openNote(<?php echo $user['Id']; ?>, '<?php echo htmlspecialchars(addslashes($user['admin_note'] ?? ''), ENT_QUOTES); ?>')">
                                        Add Note
                                    </button>
                                    <button class="btn-view-note <?php echo empty($user['admin_note']) ? 'disabled' : ''; ?>"
                                            onclick="viewNote('<?php echo htmlspecialchars(addslashes($user['admin_note'] ?? 'No notes yet.'), ENT_QUOTES); ?>', '<?php echo htmlspecialchars($user['username']); ?>')">
                                        View Note
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>


<div class="note-modal hidden" id="note-modal">
    <div class="note-box">
        <h3>Admin Note</h3>
        <form method="POST" action="">
            <input type="hidden" name="user_id" id="note-user-id">
            <textarea name="admin_note" id="note-textarea" placeholder="Write a private note about this user..."></textarea>
            <div class="note-actions">
                <button type="submit" name="save_note">Save Note</button>
                <button type="button" onclick="closeNote()">Cancel</button>
            </div>
        </form>
    </div>
</div>
<div class="note-modal hidden" id="view-note-modal">
    <div class="note-box">
        <h3>Note for <span id="view-note-username"></span></h3>
        <div class="note-content" id="view-note-content"></div>
        <div class="note-actions">
            <button type="button" onclick="closeViewNote()">Close</button>
        </div>
    </div>
</div>
    <?php
        require_once "./templates/footer.php";
    ?>
    <script src="./js/main.js"></script>
</body>
</html>