<?php
$db_dir = "sqlite:../yeelight.sqlite";
$db = new PDO($db_dir); 
$results_show = $db->query('SELECT yeelight.id, yeelight.name as name, ip_address, type.id as type_id, type.name as type_name FROM yeelight JOIN type ON type.id = yeelight.type')->fetchAll();
$results_type = $db->query('SELECT * FROM type')->fetchAll();

foreach($results_show as $product):
?>
<div class="mb-3">
    <div class="form-row align-items-center">
        <input type="number" class="form-control mb-2" id="editId<?= $product['id'] ?>" hidden value="<?= $product['id'] ?>">
        <div class="col-auto">
            <label class="sr-only" for="editName<?= $product['id'] ?>">Name</label>
            <input type="text" class="form-control mb-2" id="editName<?= $product['id'] ?>" value="<?= $product['name'] ?>">
        </div>
        <div class="col-auto">
            <label class="sr-only" for="editAddressip<?= $product['id'] ?>">IP Address</label>
            <div class="input-group mb-2">
                <input type="text" class="form-control" id="editAddressip<?= $product['id'] ?>" value="<?= $product['ip_address'] ?>">
            </div>
        </div>
        <div class="col-auto">
            <label class="sr-only" for="editType<?= $product['id'] ?>">Type</label>
            <div class="input-group mb-2">
                <select name="selectType" class="form-control" id="editType<?= $product['id'] ?>">
                    <option value="<?= $product['type_id'] ?>" selected><?= $product['type_name'] ?></option>
                    <?php foreach($results_type as $type):
                        if($type['id'] == $product['type_id'])
                        {
                            continue;
                        }
                        ?>
                        <option value="<?= $type['id'] ?>"><?= $type['name'] ?></option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
        <div class="col-auto">
            <button onclick="edit_yeelight(<?= $product['id'] ?>)" class="btn btn-primary mb-2">Edit</button>
            <button onclick="delete_yeelight(<?= $product['id'] ?>)" class="btn btn-danger mb-2">Delete</button>
        </div>
    </div>
</div>
<?php endforeach ?>