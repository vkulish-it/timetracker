<h1>Login Form</h1>
<form action="/login.php" method="post">
    <div>
        <label for="email">email:</label>
        <input type="email" name="email" id="email" placeholder="Enter your email" title="Enter your email" required>
    </div>
    <div>
        <label for="psw">password:</label>
        <input type="password" name="psw" id="psw" placeholder="Enter your password" pattern="[A-Za-z0-9]+" title="without !#$%&'()*+" minlength="4" maxlength="8" required>
    </div>
    <input type="submit" value="Submit">
</form>