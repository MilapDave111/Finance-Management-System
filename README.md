<h1 align="center">💼 PHP Finance Management System</h1>

<p align="center">
  A simple and efficient finance record system built using <strong>PHP and MySQL</strong>.<br>
  Designed for managing user data and tracking monthly financial transactions.
</p>

<hr>

<h2>📌 Features</h2>
<ul>
  <li>📇 Add new users and assign them an investment plan</li>
  <li>💰 Track monthly received amounts, dues, and payment cycles</li>
  <li>📂 Update entries anytime via web forms</li>
  <li>🔄 Backup and Restore the entire database securely</li>
</ul>

<h2>📁 Project Files</h2>
<ul>
  <li><code>config.php</code> – Connects PHP to MySQL</li>
  <li><code>data_entry.php</code> – Form for inserting user and finance records</li>
  <li><code>user.php</code> – Displays stored user information</li>
  <li><code>update.php</code> – Edit existing records</li>
  <li><code>backup.php</code> – Export database as .sql file</li>
  <li><code>restore.php</code> – Import/restore from a backup</li>
  <li><code>database.sql</code> – SQL file to initialize the DB</li>
</ul>

<h2>🧠 Database Overview</h2>
<ul>
  <li><strong>user</strong> – Stores name, amount, MIS, months, and participation date</li>
  <li><strong>finance</strong> – Linked via user_id; stores month-wise payments and cycles</li>
</ul>

<h2>🚀 How to Run</h2>
<ol>
  <li>Import <code>database.sql</code> into your phpmyadmin server</li>
  <li>Edit <code>config.php</code> to set your database credentials</li>
  <li>Place files in your local server directory (XAMPP/WAMP)</li>
  <li>Access via browser (e.g., <code>localhost/user.php</code>)</li>
</ol>

<h2>👨‍💻 Developer</h2>
<p>
  <strong>Milap Dave</strong><br>
  📧 <a href="mailto:milapdave6355@gmail.com">milapdave6355@gmail.com</a><br>
  🌐 <a href="https://www.linkedin.com/in/milap-dave-0a1422270/">LinkedIn Profile</a>
</p>
