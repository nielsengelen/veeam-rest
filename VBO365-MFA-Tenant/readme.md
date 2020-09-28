# Veeam Backup for Microsoft Office 365 RESTful API: Authentication against MFA-enabled Organizations

This is an example showing authentication against MFA-enabled organizations as explained in [the blog post](https://foonet.be/2020/09/28/veeam-backup-for-microsoft-office-365-restful-api-authentication-against-mfa-enabled-organizations/)

## 📗 Documentation

### Dependencies

Make sure you download dependencies using `composer`.

For more information on how to install `composer`:

- Linux (https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)
- Windows (https://getcomposer.org/doc/00-intro.md#installation-windows)

### Installation

#### 1. Download and install composer

a. Linux: `curl -sS https://getcomposer.org/installer | /usr/bin/php && /bin/mv -f composer.phar /usr/local/bin/composer`  
b. Windows: Download and run `Composer-Setup.exe` from the composer website.

#### 2. Clone this repository

`git clone https://github.com/nielsengelen/vbo365-rest.git`

Place these files under the web service root (`/var/www/html` or `c:\Inetpub\wwwroot`)

#### 3. Initialize Composer from the specific folder (/var/www/html or c:\Inetpub\wwwroot)

`composer install`

### Usage

Once composer has finished, open a webbrowser and go to index.php, follow the steps.

### About

This serves as an example on how to work with the RESTful API calls and code should be tested before using it in production. Feel free to modify and re-use it however many calls are done with default values which can be modified if needed.


## ✍ Contributions

We welcome contributions from the community! 

## 🤝🏾 License

- [MIT License](LICENSE)

## 🤔 Questions

If you have any questions or something is unclear, please don't hesitate to [create an issue](https://github.com/nielsengelen/veeam-rest/issues/new/choose) and let us know!
