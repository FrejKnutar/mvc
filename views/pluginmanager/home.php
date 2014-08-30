<!DOCTYPE html>
<html>
    <head>
        <?php
        Head::meta('charset', 'UTF-8');
        Head::add(dirname(get_url()) . '/views/pluginmanager/styles.css');
        Head::output();
        ?>
    </head>
    <body>
        <header>
            <h1>MVC CMS</h1>
            <h2>Content Management System</h2>
        </header>
        <nav>
            <ul>
                <li>
                    Welcome Anon:
                </li>
                <li>
                    <a href="#tour">5 Messages</a>
                </li>
                <li class="active">
                    <a href="http://localhost/mvc/">Music</a>
                </li>
                <li>
                    <a href="#video">View Site</a>
                </li>
                <li>
                    <a href="#contact">Logout</a>
                </li>
            </ul>
        </nav><aside>
            <ul>
                <li class="active">
                    <a href="http://localhost/mvc/">Music</a>
                </li>
                <ul>
                    <li><a>first item</a></li>
                    <li class="active"><a>first item</a></li>
                    <li><a>first item</a></li>
                    <li><a>first item</a></li>
                </ul>
                <li><a href="#">Plugin 2</a></li>
                <li><a href="#">Plugin 3</a></li>
                <li><a href="#">Plugin 4</a></li>
                <li><a href="#">Plugin 5</a></li>
            </ul>
        </aside><section>
            <h1>Home</h1>
            <header>
                <h2>Title</h2>
            </header>
            <article>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                <form>
                    <fieldset>
                        <legend>legend</legend>
                        <ul>
                            <li>
                                <details>details</details>
                                <label>Button asdas  asddas  dasas d asdsd dsa as dasds a dsa</label><input type="button" name="button" />
                            </li>
                            <li>
                                <details>details</details>
                                <label>Checkbox</label><input type="checkbox" name="button" />
                                <input type="checkbox" name="button" />
                                <input type="checkbox" name="button" />
                            </li>
                            <li>
                                <details>details</details>
                                <label>Color</label><input type="color"/>
                            </li>
                            <li>
                                <details>details</details>
                                <label>Date</label><input type="date"/>
                            </li>
                            <li>
                                <details>details</details>
                                <label>datetime-local</label><input type="datetime-local"/>
                            </li>
                            <li>
                                <details>details</details>
                                <label>email</label><input type="email"/>
                            </li>
                            <li>
                                <details>details</details>
                                <label>file</label><input type="file" />
                            </li>
                            <li>
                                <details>details</details>
                                <label>image</label><input type="image"/>
                            </li>
                            <li>
                                <details>details</details>
                                <label>month</label><input type="month"/>
                            </li>
                            <li>
                                <details>details</details>
                                <label>number</label><input type="number"/>
                            </li>
                            <li>
                                <details>details</details>
                                <label>password</label><input type="password"/>
                            </li>
                            <li>
                                <details>details</details>
                                <label>radio</label>
                                <input type="radio"/>
                                <input type="radio"/>
                                <input type="radio"/>
                            </li>
                            <li>
                                <details>details</details>
                                <label>range</label><input type="range"/>
                            </li>
                            <li>
                                <details>details</details>
                                <label>Date</label><input type="date" name="button" />
                            </li>
                            <li>
                                <details>details</details>
                                <label>reset</label><input type="reset"/>
                            </li>
                            <li>
                                <details>details</details>
                                <label>search</label><input type="search"/>
                            </li>
                            <li>
                                <details>details</details>
                                <label>submit</label><input type="submit"/>
                            </li>
                            <li>
                                <details>details</details>
                                <label>tel</label><input type="tel"/>
                            </li>
                            <li>
                                <details>details</details>
                                <label>text</label><input type="text"/>
                            </li>
                            <li>
                                <details>details</details>
                                <label>time</label><input type="time"/>
                            </li>
                            <li>
                                <details>details</details>
                                <label>url</label><input type="url"/>
                            </li>
                            <li>
                                <details>details</details>
                                <label>week</label><input type="week"/>
                            </li>
                        </ul>
                        <span>Description</span>
                    </fieldset>
                </form>
            </article>
            <footer><a>Read more</a></footer>
        </section>
        <footer>
            <?php //echo $spotify->createPlayer(); ?>
        </footer>
    </body>
</html>