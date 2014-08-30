<head>
    <style type="text/css">
        html, body, h1, form, fieldset, legend, ol, li {
            margin: 0;
            padding: 0;
        }
        body {
            background: #ffffff;
            color: #111111;
            font-family: Georgia, "Times New Roman", Times, serif;
            text-align: left;
            vertical-align: top;
            padding: 20px;
        }
        header, nav, aside, footer {
            background: #eeeeee;
            -moz-border-radius: 5px;
            -webkit-border-radius: 5px;
            border-radius: 5px;
            text-shadow: 1px 1px 1px #ffffff;
        }
        header, nav, article, footer {
            display: block;
            color: #888888;
            padding: 20px;
            margin: 20px 0;
        }
        header {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }
        nav ol {
            list-style: none;
            display: block;
        }
        nav ol li {
            display: inline-block
        }
        nav ol li:after {
            content: " > ";
        }
        nav ol li:last-child:after {
            content: "";
        }
        aside, section {
            display: inline-block;
            padding: 0;
            margin: 0;
            vertical-align: top;
        }
        aside {
            width: 40%;
            min-width: 200px;
        }
        section {
            width: 60%;
            min-width: 400px;
            text-align: left;
        }
        section *:first-child {
            margin-top: 0px;
        }
        section * {
            margin-left: 20px;
        }
        aside * {
            margin-left: 20px;
            margin-right: 20px;
        }
        section article {
            background: #eeeeee;
            -moz-border-radius: 5px;
            -webkit-border-radius: 5px;
            border-radius: 5px;
            text-shadow: 1px 1px 1px #ffffff;
            min-width: 400px;
            display: block;
        }
        form {
            counter-reset: fieldsets;
        }
        form fieldset {
            border: none;
            margin-bottom: 10px;
        }
        form fieldset:last-of-type {
            margin-bottom: 0;
        }
        form legend {
            color: #888888;
            font-size: 16px;
            font-weight: bold;
            padding-bottom: 10px;
        }
        form fieldset legend:before {
            counter-increment: fieldsets;
            content: "Step " counter(fieldsets) ": ";
        }
        form fieldset fieldset legend {
            color: #111111;
            font-size: 13px;
            font-weight: normal;
            padding-bottom: 0;
        }
        form ol li {
            background: #ffffff;
            background: rgba(255,255,255,.3);
            border-color: #dadada;
            border-color: rgba(255,255,255,.6);
            border-style: solid;
            border-width: 2px;
            -moz-border-radius: 5px;
            -webkit-border-radius: 5px;
            border-radius: 5px;
            line-height: 30px;
            list-style: none;
            padding: 5px 10px;
            margin-bottom: 2px;
            text-align: left;
        }
        form ol ol li {
            background: none;
            border: none;
            float: left;
        }
        form label {
            text-align: right;
            width: 100px;
            display: inline-block;
            font-size: 13px;
            padding-right: 10px;
        }
        form fieldset fieldset label {
            background:none no-repeat left 50%;
            line-height: 20px;
            padding: 0 0 0 30px;
            width: auto;
        }
        form input:not([type=radio]), form textarea {
            text-align: left;
            width: 200px;
            display: inline-block;
            text-align: left;
            background: #ffffff;
            border: none;
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
            -khtml-border-radius: 3px;
            border-radius: 3px;
            font: italic 13px Georgia, "Times New Roman", Times, serif;
            outline: none;
            padding: 5px;
        }
        form input:not([type=submit]):focus, form textarea:focus {
            background: #eaeaea;
        }
        form input[type=radio] {
            float: left;
            margin-right: 5px;
        }
        form button {
            background: #384313;
            border: none;
            -moz-border-radius: 20px;
            -webkit-border-radius: 20px;
            -khtml-border-radius: 20px;
            border-radius: 20px;
            color: #ffffff;
            display: block;
            font: 18px Georgia, "Times New Roman", Times, serif;
            letter-spacing: 1px;
            margin: auto;
            padding: 7px 25px;
            text-shadow: 0 1px 1px #000000;
            text-transform: uppercase;
        }
        form button:hover {
            background: #1e2506;
            cursor: pointer;
        }
        .error {
            color: #AA0000;
        }
    </style>
</head>
<body>
    <header>Install MVC</header>
    <nav>
        <ol>
            <?php if (isset($nav)) foreach ($nav as $step): ?>
            <li><?php echo $step;?></a>
            <?php endforeach; ?>
        </ol>
    </nav>
    <aside>
        <header>Input data into the form to the right.</header>
        <p>
            Input data into the form to the right. The data is used to create 
            the connection between the database and the system. The data will be
            stored in a local PHP file and cannot be read.
        </p>
        <p>
            In order to make a more secure system the plugins will aquire their 
            own connections that are only capable of altering data corresponding
            to plugins.
        </p>
        <p>
            The plugin user that is inputted must either exist in the database 
            with the corresponding password or the system will try to create it
            with the username and password given.
        </p>
        <p>
            If a plugin password is not inputted a random password will be 
            generated for the plugin user.
        </p>
    </aside><section>
        <header><?php if (isset($method)) echo $method;?></header>
        <article>
            <form method="POST" 
                <?php if(isset($action)) echo "action=\"$action\"";?>>
                <fieldset>
                    <?php $key = 'mysql';
                    if (isset($errors[$key])):
                        ?><ol>
                            <li class="error"><?php echo $errors[$key]; ?></li>
                        </ol>
                    <?php endif; ?>
                    <legend>Database</legend>
                    <ol>
                        <li>
                            <label for="host">Host address</label><input name="host" type="text" placeholder="Host address" required autofocus value="<?php echo isset($host) ? $host : "localhost"; ?>">
                        </li>
                        <?php $key = 'host';
                        if (isset($errors[$key])):
                            ?><li class="error"><?php echo $errors[$key]; ?></li>
                        <?php endif;
                        ?><li>
                            <label for="database">Database</label><input name="database" type="text" placeholder="Database" required value="<?php echo isset($database) ? $database : "mvc"; ?>">
                        </li>
                        <?php $key = 'database';
                        if (isset($errors[$key])):
                            ?><li class="error"><?php echo $errors[$key]; ?></li>
                        <?php endif;
                        ?><li>
                            <label for="prefix">Table prefix</label><input name="prefix" type="text" value="<?php echo isset($prefix) ? $prefix : "mvc_"; ?>">
                        </li>
                        <?php $key = 'prefix';
                        if (isset($errors[$key])):
                            ?><li class="error"><?php echo $errors[$key]; ?></li>
                        <?php endif;
                        ?><li>
                            <label for="port">Port</label><input name="port" type="number" placeholder="Port" requried value="<?php echo isset($port) ? $port : "3306"; ?>">
                        </li>
                        <?php $key = 'port';
                        if (isset($errors[$key])):
                            ?><li class="error"><?php echo $errors[$key]; ?></li>
                        <?php endif; ?>
                    </ol>
                </fieldset>
                <fieldset>
                    <legend>Database user</legend>
                    <ol>
                        <li>
                            <label for="user">User</label><input name="user" type="text" placeholder="Username" required value="<?php echo isset($user) ? $user : "root"; ?>">
                        </li>
                        <?php $key = 'user';
                        if (isset($errors[$key])):
                            ?><li class="error"><?php echo $errors[$key]; ?></li>
                        <?php endif;
                        ?><li>
                            <label for="password">Password</label><input name="password" type="password" placeholder="Password">
                        </li>
                        <?php $key = 'password';
                        if (isset($errors[$key])):
                            ?><li class="error"><?php echo $errors[$key]; ?></li>
                        <?php endif;
                        ?>
                    </ol>
                </fieldset>
                <fieldset>
                    <legend>Database user for the plugin manager</legend>
                    <ol>
                        <li>
                            <label for="pluginuser">Username</label><input name="pluginuser" type="text" required value="<?php echo isset($pluginUser) ? $pluginUser : "plugin"; ?>">
                        </li>
                        <?php $key = 'pluginUser';
                        if (isset($errors[$key])):
                            ?><li class="error"><?php echo $errors[$key]; ?></li>
                        <?php endif;
                        ?><li>
                            <label for="pluginpass">Password</label><input name="pluginpass" type="password" placeholder="Plugin Password">
                        </li>
                        <li>
                            <label></label><input type="submit" value="Submit" name="submit">
                        </li>
                    </ol>
                </fieldset>
            </form>
        </article>
    </section>
    <footer></footer>
</body>