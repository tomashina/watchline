<?php

class ControllerExtensionModuleAgmApi extends Controller {
    
    /**
     * @return void
     * @throws Exception
     */
    public function updateQuantityFromCsv(): void
    {
        $row = 1;
        $arr = [];
        
        if (($handle = fopen(DIR_APPLICATION . "../webstanje.csv", "r")) !== FALSE) {
           $i = 0;
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $arr[$data[0]] = [
                    'sku' => $i ? $data[0] : substr($data[0], 3),
                    'quantity' => $data[1],
                    'price' => str_replace(',', '.', $data[2])
                ];
                
                $i++;
                $row++;
            }
            fclose($handle);
        }
        
        $str = '';
        
        foreach ($arr as $item) {
            $str .= '("' . $item['sku'] . '", ' . intval($item['quantity']) . ', ' . $item['price'] . '),';
        }
        
        $this->db->query("TRUNCATE TABLE `temp_qty`");
        
        $this->db->query("INSERT INTO temp_qty (model, quantity, price) VALUES " . substr($str, 0, -1) . ";");

             $this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = 0  ");
        
        $this->db->query("UPDATE " . DB_PREFIX . "product p INNER JOIN temp_qty pt ON p.sku = pt.model SET p.quantity = pt.quantity, p.status = 1");
        $this->db->query("UPDATE " . DB_PREFIX . "product p INNER JOIN temp_qty pt ON p.sku = pt.model SET p.price = pt.price");
        
       $this->truncateTempDB();
        
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode(['inserted' => $row]));
    }
    
    
    /**
     * @throws \Exception
     */
    private function truncateTempDB(): void
    {
        $this->db->query("TRUNCATE TABLE `temp_qty`");
    }
}