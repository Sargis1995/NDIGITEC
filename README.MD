# Installation

- Step 1
    > ``` download source files ```
- Step 2
    >  ``` import sql dump file ```

- Step 3
    > ```Set database configuration in config.php```

    ```PHP
    <?php
    //configuration for db
    define('db_server','localhost');
    define('db_username','root');
    define('db_name','your-db');
    define('db_password','pass');
    ```
- Step 4
    ```sh
        Call your domain to load products with GET request method
    ```
- Options 
    > (int) mqty - minimum quantity that product must have
    default is 1
    domain.com/?mqty=1

    > (int) sort - sort by id 1-(ASC) 0-(DESC)
    default is 1 
    domain.com/?sort=1
    
- Responses
    - OK
    ```JSON
        
    {
        "12":{
            "id":"12",
            "title":"product12",
            "description":"Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
            "type":"product",
            "quantity":"56",
            "price":"23",
            "sku":"tz15",
            "status":"publish",
            "shop":"shop2",
            "brand":"brand1"
            }
        }
    ```
    - FAIL
    ```JSON
    {
        "error": "no products"
    }
    ```