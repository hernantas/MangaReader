<form action="handler/register.php" method="post">
	<div class="title">Register</div>
	<table cellpadding="5" cellspacing="0" border="0">
    	<tr>
        	<td>Username: </td>
            <td><input type="text" name="un" /></td>
        </tr>
        <tr><td></td><td class="desc">Input username max 64 character long. Only A-Z,a-z, 0-9 character is accepted.</td></tr>
        <tr>
        	<td>Password: </td>
            <td><input type="password" name="ps" /></td>
        </tr>
        <tr><td></td><td class="desc">Input password max 6-32. Only A-Z,a-z, 0-9 character is accepted.</td></tr>
        <tr>
        	<td>Re-type Password: </td>
            <td><input type="password" name="rps" /></td>
        </tr>
        <tr>
        	<td></td>
            <td><input type="submit" value="Register Me!!!" /></td>
        </tr>
	</table>
</form>