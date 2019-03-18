# WpAutoUpdate

Automatically update wordpress themes and plugins from the github repository.

Автоматическое обновление wordpress тем и плагинов из репозитория github.

Реагирует на релиз в репозитории.
Перейдите к вкладке Releases и щелкните по «Create a new release»
Здесь вы найдете несколько полей, которые надо заполнить. Самое важное поле — Tag version. Здесь мы поместим текущую версию релиза нашего плагина.

## Подключение для тем

В файле вашей темы добавьте

$theme = new SashokNekulin\WpAutoUpdate\ThemeUpdateg( get_template_directory() );

$theme->set_username( 'USER' ); // имя пользователя

$theme->set_repository( 'REPO' ); // репозиторий

// $theme->authorize( 'CODE_REPOS' ); // Ключ если репозиторий закрыт

$theme->initialize();

## Подключение для плагинов

В файле вашего плагина добавьте

$plugin = new SashokNekulin\WpAutoUpdate\PluginUpdate( __FILE__ );

$plugin->set_username( 'USER' ); // имя пользователя

$plugin->set_repository( 'REPO' ); // репозиторий

// $plugin->authorize( 'CODE_REPOS' ); // Ключ если репозиторий закрыт

$plugin->initialize();

## Идея взята отсюда 

http://oddstyle.ru/wordpress-2/stati-wordpress/razvertyvanie-wordpress-plaginov-cherez-github-s-pomoshhyu-transients.html


