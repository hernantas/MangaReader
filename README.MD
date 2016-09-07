MangaReader
=========

Is web based application that can manage your manga collection and provide online reading experience (since it was web based) but offline. The main use this application is for local/personal. It's possible for hosting/commercial use but not focused for that in mind (not yet).
This web is for Manga that placed on folder that has structure like this:

    Folder
    |-- Manga 1
    |    |-- Chapter 1
    |    |-- Chapter 2
    |    |-- Chapter 3
    |-- Manga 2
    |    |-- Chapter 1
    |    |-- Chapter 2
    |    |-- Chapter 3
    |-- Manga 3
    |    |-- Chapter 1
    |    |-- Chapter 2
    |    |-- Chapter 3

Feature(s)
----------
1. Provide experience when reading online locally.
2. Easy to manage manga, chapter and your reading history. It will tell you if there's new chapter, new manga and it will keep track which chapter you haven't read yet.
3. Support many user account. You can many account and each account will have it own history, and recommended list.

Installation
------------
1. Make sure you have web server & mysql server installed and active on your computer (or maybe local server like NAS). If you are using windows, you can install XAMPP or WAMPP.
2. Extract zip file and place it at your web server. (ex: "xampp/htdocs/" if using XAMPP, or "wampp/www/" if using WAMPP)
3. Access it via your web broser. For example if you name your folder mangareader and place it htdocs, you can access it via localhost/mangareader
4. Follow the installation guide when you open the web for the first time.
5. Have fun.

**Note:** Make sure you allow htaccess on your web server. XAMPP enable by default, but if you must enable it manually [See this article.](http://stackoverflow.com/questions/4321990/htaccess-not-working-on-wamp)

Feature Request, Bug/Issue report
----------------
If you encounter any bug or error, please use issues page to report any bug you found.

If you have some idea for new feature to be added, please use issues page to report. But keep in mind that not all feature can/will be implemented and more feature=this web app will become more heavy, which is you probably don't want to.

To make my job easier, please don't make new one if same or similar issues already created.

FAQ
-----
##### Q: When accessing link, it said object not found.
A:  Make sure you allow htaccess on your web server. XAMPP enable by default, but if you must enable it manually if using WAMPP. [See this article.](http://stackoverflow.com/questions/4321990/htaccess-not-working-on-wamp)

Commercial Use
--------------
You can use this for local or via hosting, but this website app is still not tested for security. I need professional security to do this, since this was free project I can't really do this. So any kind of security issue can not be guarantee. But if you found any security hole, you can report it in the issues page. Maybe in the future we will improve this, but for now we won't. Use at your own risk.
