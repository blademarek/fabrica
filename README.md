# Intro

- This is an implementation of a simple Pet Shop with Nette
- Data is stored in XML format
- The project is dockerized
- Both Bootstrap and Font Awesome are utilized


# Setup

#### To run this project live, few steps must be followed:

1. Start docker-compose (prerequisite is installed docker)
    ```
    docker-compose up
    ```

2. Enter application docker container

    ```
    docker-compose exec petshop /bin/bash
    ```
   Following commands will be run inside `petshop` container.

3. Update permissions for docker application
    ```
    chmod -R a+rw temp log content
    ```

4. Install composer packages.

   ```
   composer install
   ```


#### Optional:

If You want to access the page with different url than localhost, make sure to add hosts entry in `/etc/hosts`:
```
127.0.0.1 fabrica.local
```


# Task description

Task description is provided in Slovak language only:

### Úlohou je vytvorenie aplikácie (v Nette), ktorá bude umožňovať:

- jednoduchú správu zvieratiek v obchode s domácimi miláčikmi
- ku každému zvieratku budú evidované informácie ako meno, kategória, obrázok, status
- cez webové rozhranie (pomocou API volaní definovaných na https://petstore3.swagger.io) bude možné pridávať, editovať, vypisovať zoznam podľa statusu a mazať zvieratko
- všetky dáta o zvieratkách budú uložené v XML súbore, ktorého štruktúru si musíte navrhnúť
- pri programovaní využite OOP
- všetko ostatné, čo použijete je na Vás

### Ďalšie podúlohy, ktoré kandidátom určite pomôžu:

- aplikáciu napíšte tak, aby bola čo najjednoduchšie rozšíriteľná o ďalšie atribúty evidovné pri zvieratkách (napríklad vek zvieratka) - k vypracovaniu napíšte krátky popis ako a kde v aplikácií je potrebné urobiť zmeny, aby bolo možné pridať nový atribút
- nie je nutné, aby aplikácia obsahovala nejakú premyslenú grafiku
- k úlohe priložte sprievodný text s jednoduchým popisom riešenia - ako a prečo ste sa rozhodli spraviť veci tak, ako ste ich spravili


# Extending attributes

New attributes can be easily added following simple steps:
1. Adding new Nette Form control into `PetFormFactory.php`
2. Adjusting rendering of `shop.latte` - adding new `<th>` and `<td>` pair

# Decisions

- Docker was chosen for its ease of setup
- Working with XML has been accomplished using a combination of DOM and SimpleXMLElement, which has proven to be troublesome and a poor decision
- XML is stored in the storage directory for accessibility reasons
- Forms are implemented using a factory pattern for the purpose of reusability