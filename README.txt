		    Bitnami Redmine Stack 3.4.6-5
		  =============================

1. OVERVIEW

The Bitnami Project was created to help spread the adoption of freely
available, high quality open source web applications. Bitnami aims to make
it easier than ever to discover, download and install Open Source software 
such as document and content management systems, wikis and blogging 
software.

You can learn more about Bitnami at https://bitnami.com

Redmine is a flexible project management web application. Written in Ruby on 
Rails freamework, it is a cross-platform and cross-database.

You can learn more about Redmine at http://redmine.org

The Bitnami Redmine Stack is an installer that greatly simplifies the
installation of Redmine and runtime dependencies. It includes ready-to-run
versions of Apache, MySQL, Subversion and Ruby on Rails. Redmine Stack is 
distributed for free under the Apache 2.0 license. Please see the appendix 
for the specific licenses of all Open Source components included.

You can learn more about Bitnami Stacks at https://bitnami.com/stacks/

2. FEATURES

- Easy to Install

Bitnami Stacks are built with one goal in mind: to make it as easy as
possible to install open source software. Our installers completely automate
the process of installing and configuring all of the software included in
each Stack, so you can have everything up and running in just a few clicks.

- Independent

Bitnami Stacks are completely self-contained, and therefore do not interfere
with any software already installed on your system. For example, you can
upgrade your system's MySQL or Apache without fear of 'breaking' your
Bitnami Stack.

- Integrated

By the time you click the 'finish' button on the installer, the whole stack
will be integrated, configured and ready to go. 

- Relocatable

Bitnami Stacks can be installed in any directory. This allows you to have
multiple instances of the same stack, without them interfering with each other. 

3. COMPONENTS

Bitnami Redmine Stack ships with the following software versions:
 
  - Redmine 3.4.6
  - Apache 2.4.37
  - ImageMagick 6.9.8-3-Q8
  - MySQL 5.6.42
  - Subversion 1.11.0
  - Git 2.19.1
  - Ruby 2.3.3-i386-mingw32
  - Rails 4.2.8
  - RubyGems 2.6.14


4. REQUIREMENTS

To install Bitnami Redmine Stack you will need:

    - Intel x86 or compatible processor
    - Minimum of 512 MB RAM
    - Minimum of 400 MB hard drive space
    - TCP/IP protocol support
    - Compatible operating systems:
      - An x86 Linux operating system.
      - A 32-bit Windows operating system such as Windows Vista, Windows 7, Windows 8, Windows 10, Windows Server 2008 or Windows Server 2012.
	  - An OS X operating system.
      


5. INSTALLATION

The Bitnami Redmine Stack is distributed as a binary executable installer.
It can be downloaded from:

https://bitnami.com/stacks/

The downloaded file will be named something similar to:

bitnami-redmine-3.4.6-5-linux-installer.run on Linux or
bitnami-redmine-3.4.6-5-linux-x64-installer.run on Linux 64 bits or
bitnami-redmine-3.4.6-5-windows-installer.exe on Windows or
bitnami-redmine-3.4.6-5-osx-x86-installer.dmg on OS X x86.

On Linux, you will need to give it executable permissions:

chmod 755 bitnami-redmine-3.4.6-5-linux-installer.run

To begin the installation process, invoke from a shell or double-click on
the file you have downloaded, and you will be greeted by the 'Welcome'
screen. You will be asked to choose the installation folder. If the
destination directory does not exist, it will be created as part of the
installation.

The default listening port for Apache is 8080, for MySQL is 3006, for
Subversion is 3690 and, only on Windows, for Thin cluster are 3001 and 3002. 
If those ports are already in use by other applications, you will be 
prompted for alternate ports to use.

The next screen will prompt you for data necessary to create the initial
admin user: 

Username and password: You will use this information to log-in into the
administrative interface. The password you provide here will also be used to
protect other parts of the installation. Please see the section named
"Usernames and Passwords" later in this document.

Real name: The name that will be displayed in the application.

Email address: Your email address.

You can select the language to load the default data into the next page.

The next screen will prompt you for data necessary to send emails:

SMTP Address: The address of SMTP server.

SMTP Hostname: The name of SMTP server.

SMTP Port: The port of SMTP server.

SMTP Username and Password: User and password to SMTP server.

Once the installation process has been completed, you will see the
'Installation Finished' page. You can choose to launch Bitnami
Redmine Stack at this point. If you do so, your default web browser
will point you to the Bitnami local site.

If you receive an error message during installation, please refer to
the Troubleshooting section.

The rest of this guide assumes that you installed Bitnami Redmine
Stack in /home/user/redmine-3.4.6-5 on Linux or
/Application/redmine-3.4.6-5 on OS X or
C:\Program Files\Bitnami Redmine Stack on Windows and use port 8080 for Apache
on Unix systems or 80 on Windows, 3306 for MySQL, 3690 for Subversion and, only
on Windows, 3001 and 3002 for Thin cluster.

6. STARTING AND STOPPING BITNAMI REDMINE STACK

To enter to Redmine you can point your browser to
http://127.0.0.1:8080/ on Unix systems or http://127.0.0.1/ on Windows.

To start/stop/restart application on Linux you can use the included 
ctlscript.sh utility, as shown below:

       ./ctlscript.sh (start|stop|restart)
       ./ctlscript.sh (start|stop|restart) mysql
       ./ctlscript.sh (start|stop|restart) apache
       ./ctlscript.sh (start|stop|restart) redmine
       ./ctlscript.sh (start|stop|restart) subversion

  start      - start the service(s)
  stop       - stop  the service(s)
  restart    - restart or start the service(s)

You can start and stop Bitnami Redmine Stack on Windows using the shortcuts created
in the Start Menu, under Programs -> Bitnami -> Bitnami Service 

That will start Apache, Subversion, Redmine and MySQL services. Once started, 
you can open your browser and access the following URL:

http://127.0.0.1:8080/ on Unix systems or

http://127.0.0.1/ on Windows.

If you selected an alternate port during installation, for example 18080, the
URL will look like:

http://127.0.0.1:18080/


7. DIRECTORY STRUCTURE

The installation process will create several subfolders under the main
installation directory:

	apache2/: Apache Web server.
	mysql/: MySQL Database.
	ruby/: Ruby files.
	subversion/: Subversion files.
	imagemagick/: ImageMagick image proccessor.
	common/: Common libraries.
	licenses/: Licenses files.
	scripts/: Script with enviromnent vars.
	apps/redmine/htdocs: Redmine files.
	

8. DEFAULT USERNAMES AND PASSWORDS

The Redmine administrative user and password are the ones you set at
installation time. 

MySQL admin user is called 'root', and its password is also one
you set at installation time.

The default non-root account used to access the database is named
bitnami, and its password is also bitnami.

9. TROUBLESHOOTING

This version of the Bitnami Redmine Stack is a preview version, and as
such, may contain a number of bugs and be a little bit rough around the
edges. We are working on the next release, which will contain several
improvements along with expanded documentation. In addition to the resources
provided below, we encourage you to post your questions and suggestions at:

https://community.bitnami.com/

We also encourage you to sign up for our newsletter, which we'll use to
announce new releases and new stacks. To do so, just register at:
https://bitnami.com/newsletter.

9.1 Installer

# Installer Payload Error 

If you get the following error while trying to run the installer from the
command line:

"Installer payload initialization failed. This is likely due to an
incomplete or corrupt downloaded file" 

The installer binary is not complete, likely because the file was
not downloaded correctly. You will need to download the file and
repeat the installation process.

# Installer execution error on Linux

If you get the following error while trying to run the installer:

"Cannot open bitnami-redmine-3.4.6-5-linux.run: No application suitable for 
automatic installation is available for handling this kind of file."

In some operatings systems you can change permissions with right click ->
properties -> permissions -> execution enable. 

Or from the command line:

$ chmod 755 bitnami-redmine-3.4.6-5-linux.run 


9.2 Apache 

If you find any problem starting Apache, the first place you should check is
the Apache error log file:

/home/user/redmine-3.4.6-5/apache2/logs/error.log on Linux or
/Applications/redmine-3.4.6-5/apache2/error.log on OS X or
C:\Program Files\Bitnami Redmine Stack\apache2\logs\error.log on Windows.

Most errors are related to not being able to listen to the default port.
Make sure there are no other server programs listening at the same port
before trying to start Apache. Some programs, such as Skype, may also use
port 80. For issues not covered in this Quick Start guide, please refer to
the Apache documentation, which is located at http://httpd.apache.org/docs/  

9.3 MySQL

If you encounter any problems starting MySQL, the first place to
look in is the "Problems and Common Errors" section of the MySQL
manual, which you will find at http://dev.mysql.com/doc/ The
following are some common problems: 

# Access denied when trying to connect to MySQL. 

If you get an Access Denied message while trying to connect to
MySQL, make sure you are using the correct username and password. 

# "Can't connect to server" message. 

Make sure that the MySQL daemon is up and running. Remember also that if
during installation you selected a different listening port for MySQL, you
may need to pass that as an extra command line option.


9.4 Subversion

This section describes how to start the svnserver and to create a simple repository
with Subversion. You can find th Subversion technical documentation at 
http://subversion.tigris.org/.

You can start stop Subversion server with ctlscript.sh or using the shortcuts on
Windows. You can create a repository with:

$ svnadmin create /path/to/repository  on Unix systems or
$ svnadmin.exe create c:\path\to\repostiroy on Windows.

Now, we can configure the repository. The repository's
svnserve.conf file is the central mechanism for
controlling authentication and authorization policies. You can
edit this file in /path/to/repository/conf/svnserce.conf.

The variables anon-access  and auth-access can be set to the values
none, read, or write. Setting the value to none prohibits both
reading and writing; read allows read-only access to the
repository, and write allows complete read/write access to the
repository. For example, you can uncomment these lines:</p>

[general]
anon-access = read
auth-access = write
password-db = passwd

You can edit passwd file at the same directory to manage
subversion users. For example, if you uncomment these lines you
create two subversion users; harry and sally.</p>

[users]
harry = harryssecret
sally = sallyssecret

It is necessary to restart svnserve to apply the changes.

Finally you can import a project directory to subversion.

$ svn import /path/to/project/ svn://localhost/path/to/repository/ -m "first import"


9.5 Redmine

For any problem related to Redmine, please visit
http://redmine.org

Japanese translation kindly contributed by Kei SAKAI from ARTRY Ltd.
http://artry.co.jp/  (twitter: @sakaik)

10. LICENSES

Redmine is open source and released under the terms of the GNU General 
Public License v2 (GPL2), wich is located at http://www.gnu.org/licenses/old-licenses/gpl-2.0.html

Apache Web Server is distributed under the Apache License v2.0, which
is located at http://www.apache.org/licenses/LICENSE-2.0

MySQL is distributed under the GNU General Public License v2, which is
located at http://www.gnu.org/licenses/old-licenses/gpl-2.0.html

Ruby is released under the Ruby License, wich is located at
http://www.ruby-lang.org/en/LICENSE.txt

Rails is released under the MIT license, which is located 
http://www.opensource.org/licenses/mit-license.php

Subversion is released under Subversion License / released under the 
terms of the Apache License, which is located at
http://subversion.tigris.org/license-1.html

RubyGems is released under the Ruby License, which is located at
http://www.ruby-lang.org/en/LICENSE.txt

Rake is released under the Ruby License, which is located at
http://www.ruby-lang.org/en/LICENSE.txt

Thin Web Server is released under the Ruby License, which 
is located at http://www.ruby-lang.org/en/LICENSE.txt

ImageMagick has its own license, which is located at
https://www.imagemagick.org/subversion/ImageMagick/trunk/LICENSE

Rmagick is released under the MIT license, which is located 
http://www.opensource.org/licenses/mit-license.php

Capistrano is released under the MIT/X Consortium License, whic is
located at http://dev.rubyonrails.org/browser/tools/capistrano/MIT-LICENSE?rev=5270

OpenSSL is released under the terms of the Apache License, which is
located at http://www.openssl.org/source/license.html

Ncurses is released under the MIT license, which is located at
http://www.opensource.org/licenses/mit-license.php

Readline is released under the GPL license, which is located at
http://www.gnu.org/copyleft/gpl.html

Zlib is released under the zlib License (a free software license/compatible 
with GPL), which is located at http://www.gzip.org/zlib/zlib_license.html

Libiconv is released under the LGPL license, which is located at
http://www.gnu.org/licenses/lgpl.html

Expat is released under the MIT license, which is located at
http://www.opensource.org/licenses/mit-license.php

Neon is released under the GNU General Public License, which is located at
http://www.gnu.org/copyleft/gpl.html

RedCloth is released under the BSD License, which is located at
http://www.opensource.org/licenses/bsd-license.php

ImageMagick has its own license, which is located at
https://www.imagemagick.org/subversion/ImageMagick/trunk/LICENSE

Rmagick is released under the MIT license, which is located 
http://www.opensource.org/licenses/mit-license.php

Freetype is released under The Freetype Project License, that is located at
http://freetype.sourceforge.net/FTL.TXT
