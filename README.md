<h1>Routes you can call on thie Symfony project</h1>

Main URL : <a href="https://cookscape-domain.up.railway.app">https://cookscape-domain.up.railway.app</a>

  <h2>Dishes</h2>
    create_dish                     POST     ANY      ANY    <strong>/dishes/create</strong>
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

    list_dishes                     GET      ANY      ANY    <strong>/dishes/list</strong>
      return{
          'id',
          'name'
      }

    get_dish                        GET      ANY      ANY    <strong>/dishes/get/{name}-{id}</strong>
      data{
        *on url change name and id by the corresponding dish
      }
      return{
          'id',
          'name',
          'recipe'
      }

    app_dish_cancel                 DELETE   ANY      ANY    <strong>/dishes/cancel/{id}</strong>
      data{
        *on url change name and id by the corresponding dish
      }

    app_dish_delete                 DELETE   ANY      ANY    <strong>/dishes/delete/{id}</strong>
      data{
        *on url change name and id by the corresponding dish
      }

  <h2>Firebase Connection</h2>
    firebase_test                   ANY      ANY      ANY    <strong>/firebase/test</strong>
      return new JsonResponse(['message' => 'Firebase connected successfully!']);
      
    firebase_login                  POST     ANY      ANY    <strong>/firebase/login</strong>
      data{
        'email'
        'password'
      }
      return{
        'idToken'
      }
    
    firebase_verify                 POST     ANY      ANY    <strong>/firebase/verify</strong>
      data{
        Header Bearer => Token
      }

    firebase_save                   POST     ANY      ANY    <strong>/firebase/save</strong>
      data{
        'collection'
        'documentId'
        'fields'{}
      }
      
    firebase_get                    POST     ANY      ANY    <strong>/firebase/get</strong>
      data{
        'collection'
        'documentId' *optional
      }

  <h2>Ingredients</h2>
    create_ingredients_category     POST     ANY      ANY    <strong>/ingredients-category/create</strong>
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

    list_ingredients_category       GET      ANY      ANY    <strong>/ingredients-category/list</strong>
      return{
        'id'
        'name'
      }

    get_ingredients_from_category   GET      ANY      ANY    <strong>/ingredients-category/get/{name}-{id}</strong>
      data{
        *on url change name and id by the corresponding ingredients by category
      }
      return{[
        'id'
        'name'
      ]}
    
    create_ingredients              POST     ANY      ANY    <strong>/ingredients/create</strong>
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
    
    update_ingredient_quantity      POST     ANY      ANY    <strong>/ingredients/update-quantity</strong>
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
      
    list_ingredients                GET      ANY      ANY    <strong>/ingredients/list</strong>
      return{
        'id'
        'name'
        'quantity'
      }
    
    show_ingredients                GET      ANY      ANY    <strong>/ingredients/detail/{name}-{id}</strong>
      return{
        'id'
        'name'
        'quantity'
        'Category':{}
      }

  <h2>Recipes</h2>
    create_recipe                   POST     ANY      ANY    <strong>/recipes/create</strong>
      data{
        'dish_id'
        'ingredient' : [{
          'id',
          'quantity'
        }]*Must be Array of ingredients
      }
      
    add_to_recipe                   POST     ANY      ANY    <strong>/recipes/add</strong>
      data{
        'dishId',
        'ingredient':{
          'id'
          'quantity'
        }
      }
    
    list_recipe                     GET      ANY      ANY    <strong>/recipe/list</strong>
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
