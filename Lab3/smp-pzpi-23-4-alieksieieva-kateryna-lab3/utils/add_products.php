  <?php
       $db = new PDO('sqlite:database.sqlite');
       $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     
       $products = [
           ['name' => 'Яблука (1 кг)', 'price' => 40],
           ['name' => 'Банани (1 кг)', 'price' => 35],
           ['name' => 'Молоко 1 л', 'price' => 25],
           ['name' => 'Хліб', 'price' => 20],
          ['name' => 'Масло верщкове 200 г', 'price' => 90],
          ['name' => 'Сир 1 кг', 'price' => 300],
          ['name' => 'Курка (1 кг)', 'price' => 150],
          ['name' => 'Яловичина (1 кг)', 'price' => 400],
          ['name' => 'Свинина (1 кг)', 'price' => 350],
          ['name' => 'Картопля (1 кг)', 'price' => 15],
          ['name' => 'Морква (1 кг)', 'price' => 20],
          ['name' => 'Цибуля (1 кг)', 'price' => 18],
          ['name' => 'Яйця 10 шт', 'price' => 40],
          ['name' => 'Рис (1 кг)', 'price' => 50],
          ['name' => 'Макарони (1 кг)', 'price' => 45],
          ['name' => 'Томати (1 кг)', 'price' => 55],
          ['name' => 'Огірки (1 кг)', 'price' => 50],
          ['name' => 'Кефір 1 л', 'price' => 30],
          ['name' => 'Цукор (1 кг)', 'price' => 35],
          ['name' => 'Чай (100 г)', 'price' => 70],
      ];
    
      $stmt = $db->prepare("INSERT INTO products (name, price) VALUES (:name, :price)");
    
      foreach ($products as $product) {
          $stmt->execute([
              ':name' => $product['name'],
              ':price' => $product['price'],
          ]);
      }
    
      echo "Продукти харчування додані.";
