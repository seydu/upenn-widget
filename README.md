**Upenn recruitement project**

This is an implementation of [Widget exercise
](https://gist.github.com/barendt/4dbfeeda803fc47677be).

### How to run the application
This application is built on Symfony 3.4, installed by Symfony Flex.
It uses a SQL database. The code comes with a SQLite database loaded with fixtures data.
But it is possible to rebuild the database and reload the fixtures by running the script
bin/reset_db.sh from the project root directory:
```bash
./bin/reset_db.sh
```

Goto to ​http://upenn-widget.ld:8000​ (it assumes ​ upenn-widget ​ is the name of the virtual host.
and the port is 8000).
This page contains two links: one for creating orders and one for administration.

### Emails
Emails are handed over to the mailer service. This service is configured to only send the emails
to the logger. In a production environment this service will be configured to send real emails.
Create an order, then go to the profiler page
(​http://upenn-widget.ld:8000/_profiler/empty/search/results?limit=10​).
Select the request that corresponds to the order creation request (most recent POST with the
url ​http://upenn-widget.ld:8000/order​). Click on the ‘E-mails’ tab on the left pane. It will display
sent emails. There will be one email sent. Select its ‘Rendered content’ tab to access the
rendered HTML.

### Testing
Balancing tests is hard (test enough to cover specifications and capture breaks introduced by changes but don’t target
100% test coverage). I usually follow the principal of not unit testing very simple application logic
and relying on functional tests for it. This is one of the benefits of using well tested libraries and
frameworks. For instance when I have a custom choice loader for a form type or validator I only
unit test the loader or validator. For the rest I trust Symfony Form Component enough to use it
in functional test to check for correctness.
There are some code repetitions in the tests (especially in the code testing widget order
creation). I have learned to allow those repetitions until there is a sizeable set of tests before
refactoring them (we don’t want to write tests for our tests!). Then reduce code repetition in the
tests wisely.
To run the test, execute this command from the project root:
```bash
./bin/phpunit
```
### Designing the database
I used ​Skipper​ to design the database. The editor is not free but the visualizer is. There is a
[UpennWidget.skipper](/UpennWidget.skipper) file at the project root that can be used to view an interactive version. The
image file [UpennWidget.png](/UpennWidget.png) is a static export of the UML. Skipper can export the schema into
Doctrine mapping files in PHP annotations (used here) or XML. Big time saver.
Skipper has its limits. When features of a database that are not supported by the ORM are
used, Skipper cannot represent them in a two-way synchronization. But overall it helps keep an
easy to read documentation of the data model and very closely synchronized with the concrete
database schema.



