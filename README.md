<h1>Routes you can call on thie Symfony project</h1>

Main URL : <a href="https://cookscape-domain.up.railway.app">https://cookscape-domain.up.railway.app</a>

  <h2>Dishes</h2>
    create_dish                     POST     ANY      ANY    /dishes/create
      data{
        "name":String
      }
      return{
        'message' => 'Dish created successfully',
            'dish' => [
                'id' => $dish->getId(),
                'name' => $dish->getName(),
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

  <h2>Recipes</h2>
    create_recipe                   POST     ANY      ANY    /recipes/create
      data{
        'dish_id'
        'ingredient' : [{
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
