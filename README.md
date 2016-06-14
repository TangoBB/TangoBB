[![TangoBB](https://raw.githubusercontent.com/Codetana/TangoBB/master/public/img/tangobb_logo.png "TangoBB")](http://tangobb.com "TangoBB")
##2.0 Branch
This branch facilitates the development of TangoBB 2.0. In this major development, we have decided that developing TangoBB in an MVC structure would be more appropriate. The list of planned dependencies will be listed down below.

Site: [https://alpha.tangobb.com/](https://alpha.tangobb.com/)
Poll: [http://www.strawpoll.me/10413269](http://www.strawpoll.me/10413269)

##Dependencies

- Laravel Framework
- [igaster/laravel-theme](https://github.com/igaster/laravel-theme)
- [guzzlehttp/guzzle](https://github.com/guzzle/guzzle)
- [WysiBB Editor](https://github.com/wbb/wysibb)
- More TBD

##Install
*Do not use this in production.

In order to try TangoBB 2.0 out in it's current development phase, you can clone the repository.
####Commands to Run

- `composer install`
- `php artisan key:generate`
- `php artisan migrate`
- `php artisan db:seed`