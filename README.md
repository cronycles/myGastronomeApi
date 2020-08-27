#My Gastronome Api
* Repository: git@github.com:cronycles/myGastronomeApi.git

## Scaricare per la prima volta il progetto in locale
* scaricare il progetto git
```
$ git clone git@github.com:cronycles/myGastronomeApi.git
```
* portarsi con il terminale nella cartella del progetto web:
```
$ cd myGastronomeApi
```
* Eseguire i seguenti comandi per installare i pacchetti necessari
```
$ npm install
$ composer install
$ composer update --no-scripts
```
* Adesso PHP Artisan:
```
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
php artisan config:cache
```
### .env file
Il file non sta nel repository per ovvie ragioni di password. 
Peró per lanciare il progetto Laravel avete bisogno di un file **.env**.
I file per il progetto si trovano nella **wiki personale**, precisamente nella sezione **http://www.crointhemorning.com/wiki/index.php/Crointhemorning#MyGastronomeApi**
* creare dunque il file _.env_ e copiarne il contenuto dalla wiki
* aprire il nuovo file creato
* aggiustare i parametri, soprattutto quelli del db
* copiare poi anche quello di produzione creando un _.env.production_
* lanciare poi

```
php artisan key:generate
php artisan config:cache
```
* assicuratevi che dentro il vostro .env abbia generato la chiave della app in *APP_KEY*
* dopodiché lanciate:

```
$ php artisan migrate
$ php artisan db:seed
```
* lanciare il _npm run dev_

* dare al play:
```
 $ php artisan serve
```

## Deploy a Production
prima di lanciarlo assicurarsi di:
>* avere installato nel proprio pc jq [Usa Homebrew con **brew install jq**]
>* avere il branch **develop** e **master** in locale
>* avere il branch **develop** tracked con **origin**

* Aprire il terminale e digitare UNO dei seguuenti tre comandi

```
npm run build
```
> Questo comando lancia solo un build degli assets in modo production (minificato, no sourcemaps, ...)


```
npm run build-minor
```
> Questo comando creerá una versione Minor della app e subirá il contenuto automaticamente a master.

```
npm run build-mayor
```
> Stessa cosa per questo che creerá una versione Mayor della app e subirá il contenuto automaticamente a master.

* Con gli ultimi due comandi, automaticamente verrá anche creato un tag con la versione
* Poi bisognerá solo andare nel **CPanel** del vostro provider, nella sezione **Git Version Control**.
* Vedrete che c'è gia un repository con un bottone **Gestione**, cliccarlo.
* Vi si aprirá una pagina con due tabs, andare al tab **Pull or Deploy**
* Cliccare in ordine i 2 bottoni: **Update from remote** e poi **Deploy HEAD commit**
Tutto il deploy lo fará solo grazie al file che avete in questo progetto chiamato **.cpanel.yml**

### Prima volta che fate il deploy a Production?
Se é la prima volta, allora dovete prima assicurarvi, nella route del progetto, di avere le seguienti cose giá messe a mano:
* la cartella __/storage__
* il file __.env.production__ che avrete rinominato in __.env__
* la cartella del vostro cdn
* ovviamente la BBDD funzionante

## CDN
Per comoditá le immagini del progetto uploaded e gli eventuali files sono stati messi in un subdominio chiamato **cdn.hirikoestudio.com**.
Tutto questo serve per non tenere i file dentro il progetto stesso, che creerebbero casini alla ora di fare deploy o eliminare cose.
### cdn remoto
il path dei file é dentro la root del sito e, se entrate con il cpanel incontrerete la cartella **cdn.hirikoestudio.com** proprio nella **root**
### cdn locale
qui siccome non é scomodo creare un altro dominio etc. La cartella é dentro **public_html**. 
Peró non vi preoccupate. al momento del deploy la cartella verrá eliminata e non verrá sporcato il **public_html** di produzione
