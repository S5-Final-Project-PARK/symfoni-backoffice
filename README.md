<h1>Routes you can call on thie Symfony project</h1>

Main URL : <a href="https://cookscape-domain.up.railway.app">https://cookscape-domain.up.railway.app</a>

  <h2>Dishes</h2>
  
    create_dish                     POST     ANY      ANY    /dishes/create
      data{
        "name":String
        "price": Number
      }
      return{
        'message' => 'Dish created successfully',
            'dish' => [
                'id' => $dish->getId(),
                'name' => $dish->getName(),
                'price' => $dish->getPrice()
            ]
      }

    list_dishes                     GET      ANY      ANY    /dishes/list
      return{
          'id',
          'name'
      }

    get_dish                        GET      ANY      ANY    /dishes/get/{name}-{id}
      data{
        *on url change name and id by the corresponding dish
      }
      return{
          'id',
          'name',
          'recipe'
      }

    app_dish_cancel                 DELETE   ANY      ANY    /dishes/cancel/{id}
      data{
        *on url change name and id by the corresponding dish
      }

    app_dish_delete                 DELETE   ANY      ANY    /dishes/delete/{id}
      data{
        *on url change name and id by the corresponding dish
      }

  <h2>Firebase Connection</h2>
  
    firebase_test                   ANY      ANY      ANY    /firebase/test
      return new JsonResponse(['message' => 'Firebase connected successfully!']);
      
    firebase_login                  POST     ANY      ANY    /firebase/login
      data{
        'email'
        'password'
      }
      return{
        'idToken'
      }

    firebase_create_user            POST     ANY      ANY    /firebase/create-user
        data{
          'email'
          'password'
          'role' * Only 'user' or 'admin'
        }

        return{
          'email'
          'uid'
          'role'
          *doesn't return idToken, have to go login
        }
    
    firebase_verify                 POST     ANY      ANY    /firebase/verify
      data{
        Header Bearer => Token
      }

    firebase_save                   POST     ANY      ANY    /firebase/save
      data{
        'collection'
        'documentId'
        'fields'{}
      }
      
    firebase_get                    POST     ANY      ANY    /firebase/get
      data{
        'collection'
        'documentId' *optional
      }

  <h2>Ingredients</h2>
  
    create_ingredients_category     POST     ANY      ANY    /ingredients-category/create
      data{
        "name":String
      }
      return{
        'message' => 'IngredientsCategory created successfully',
            'category' => [
                'id' => $dish->getId(),
                'name' => $dish->getName(),
            ]
      }

    add_ingredient_quantity         POST     ANY      ANY    /ingredients/add-quantity
    data{
        'ingredients_id'
        'added_quantity'
      }
      return{
        'message' => 'Ingredient quantity updated successfully',
            'ingredient' => [
                'id' => $ingredient->getId(),
                'name' => $ingredient->getName(),
                'old_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
            ]
      }

    list_ingredients_category       GET      ANY      ANY    /ingredients-category/list
      return{
        'id'
        'name'
      }

    get_ingredients_from_category   GET      ANY      ANY    /ingredients-category/get/{name}-{id}
      data{
        *on url change name and id by the corresponding ingredients by category
      }
      return{[
        'id'
        'name'
      ]}
    
    create_ingredients              POST     ANY      ANY    /ingredients/create
      data{
        'name'
        'idCategory'
      }
      return{
        'message' => 'Ingredient created successfully',
            'ingredient' => [
                'id' => $ingredient->getId(),
                'name' => $ingredient->getName(),
                'category' => $ingredient->getIdCategory()->getName(), // Assuming `getName()` exists in IngredientsCategory
            ]
      }
    
    update_ingredient_quantity      POST     ANY      ANY    /ingredients/update-quantity
      data{
        'ingredients_id'
        'new_quantity'
      }
      return{
        'message' => 'Ingredient quantity updated successfully',
            'ingredient' => [
                'id' => $ingredient->getId(),
                'name' => $ingredient->getName(),
                'old_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
            ]
      }
      
    list_ingredients                GET      ANY      ANY    /ingredients/list
      return{
        'id'
        'name'
        'quantity'
      }
    
    show_ingredients                GET      ANY      ANY    /ingredients/detail/{name}-{id}
      return{
        'id'
        'name'
        'quantity'
        'Category':{}
      }
    
    logs-ingredients                GET      ANY      ANY    /ingredients/logs
    return{
      'id'
      'ingredients':{}
      'oldQuantity'
      'newQuantity'
      'updatedAt'
    }

  <h2>Recipes</h2>
  
    create_recipe                   POST     ANY      ANY    /recipes/create
      data{
        'dish_id'
        'ingredients' : [{
          'id',
          'quantity'
        }]*Must be Array of ingredients
      }
      
    add_to_recipe                   POST     ANY      ANY    /recipes/add
      data{
        'dishId',
        'ingredient':{
          'id'
          'quantity'
        }
      }
    
    list_recipe                     GET      ANY      ANY    /recipe/list
      return{
        ingredients:{
          'id',
          'name'
        }
        dish:{
          'id'
          'name'
        }
      }

  <h2>Orders</h2>

    save_order                      POST     ANY      ANY    /orders/save
      data{
          'date'(YY-MM-DD)
          'dishes': {"name", "unit"}
          'email'
      }
    get_orders                      GET      ANY      ANY    /orders/get
      return{[
          'id'
          'unit'
          'unit_price'
          'Dish':{
              'id',
              'name',
              'recipe':{}
          }
          'email'
          'date'
      ]}
    
    update_order_confirmation       POST     ANY      ANY    /orders/update/{id}
      data{
        *on url change id by the corresponding order
      }
