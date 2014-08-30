<!DOCTYPE html>
<html>
    <head>
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA5Gj7ZgyJp4YkhqjbUE5dwc5sQBj20P8o"></script>
        <?php
        Head::meta('charset', 'UTF-8');
        Head::add(dirname(get_url()) . '/views/band/styles.css');
        Head::add(dirname(get_url()) . '/views/band/navigation.js');
        Head::output();
        ?>
    </head>
    <body>
        <header class="clear">
            <h1>Title</h1>
            <h2>subtitle</h2>
        </header>
        <nav>
            <ul>
                <li>
                    <a href="#home">Home</a>
                </li>
                <li>
                    <a href="#tour">Tour</a>
                </li>
                <li>
                    <a href="#music">Music</a>
                </li>
                <li>
                    <a href="#video">Video</a>
                </li>
                <li>
                    <a href="#bio">Bio</a>
                </li>
                <li>
                    <a href="#contact">Contact</a>
                </li>
            </ul>
        </nav>
        <div id="slider">
            <div class="inner">
                <ul>
                    <li name="home">
                        <section>
                            <h1>Home</h1>
                            <header>
                                Title
                            </header>
                            <article>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                            </article>
                            <footer><a>Read more</a></footer>
                        </section><aside>Aside</aside>
                    </li>
                    <li name="tour">
                        <section class="tour">
                            <header>Tour</header>
                            <article id="tour">
                                <ol>
                                    <li class="city">City</li><li class="location">Location</li><li class="time">Time</li><li class="date">Date</li><li class="tickets">tickets</li>
                                </ol>
                                <ul active="true">
                                    <li class="city">Stockholm</li><li class="location">Kungliga Operan</li><li class="time">00:00 - 12:00</li><li class="date">31 Jan</li><li class="tickets"><a href="http://www.ticnet.se" target="_blank">tickets</a></li>
                                    <div>
                                        <ul>
                                            <p></p>
                                        </ul><div class="map" title="Södermanlands-Nerikes nation" lat="59.858905" lng="17.630169" zoom="15"></div>
                                    </div>
                                </ul>
                                <ul>
                                    <li class="city">Uppsala</li><li class="location">Snärkes</li><li class="time">00:00 - 12:00</li><li class="date">12 Jan</li><li class="tickets"><a href="http://www.ticnet.se" target="_blank">tickets</a></li>
                                </ul>
                            </article>
                            <footer></footer>
                        </section><aside>Aside</aside>
                    </li>
                    <li name="music">
                        <section>
                            <h1>Music</h1>
                            <header>Link 2</header>
                            <article>Article</article>
                            <footer></footer>
                        </section><aside>Aside</aside>
                    </li>
                    <li name="video">
                        <section>
                            <h1>Video</h1>
                            <header>Jazz på ryska</header>
                            <article>
                                <iframe width="100%" height="315" src="//www.youtube.com/embed/YouARXfbqvc?list=PLOwbJ2VNhqRv7xFvuXapYuE-qQ1NM61wQ" frameborder="0" allowfullscreen></iframe>
                            </article>
                            <footer></footer>
                        </section><aside>Aside</aside>
                    </li>
                    <li name="bio">
                        <section>
                            <header>Link 2</header>
                            <article>Article</article>
                            <footer></footer>
                        </section><aside>Aside</aside>
                    </li>
                    <li name="contact">
                        <section>
                            <h1>Contact</h1>
                            <header>If you would like to book our band please enter the booking information in the form below</header>
                            <article>
                                <form>
                                    <fieldset>
                                        <legend>Booking Information</legend>
                                        <ul>
                                            <li><label for="name">Name:</label><input type="text" name="name" /></li>
                                            <li>
                                                <ol>
                                                    <li><label>First</label><input type="radio" name="radio" value="1" /></li>
                                                    <li><label>Second</label><input type="radio" name="radio" value="2" /></li>
                                                </ol>
                                            </li>
                                            <li><label for="text">Name:</label><textarea name="name" ></textarea></li>
                                        </ul>
                                    </fieldset>
                                    <fieldset>
                                        <legend>Media Inquiries</legend>
                                    </fieldset>
                                </form>
                            </article>
                            <footer></footer>
                        </section><aside>Aside</aside>
                    </li>
                </ul>
            </div>
        </div>
        <footer>
            <?php //echo $spotify->createPlayer(); ?>
        </footer>
    </body>
</html>