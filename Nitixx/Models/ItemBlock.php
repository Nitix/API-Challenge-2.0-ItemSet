<?php


namespace Nitixx\Models;


use LeagueWrap\Dto\StaticData\Item;
use Nitixx\Controllers\ApiManager;
use Nitixx\Controllers\DatabaseManager;

class ItemBlock implements DBObjectInterface
{

    /**
     * @var int ID of the block, used for the database
     */
    private $id;

    /**
     * @var Block Block of the current itemBlock
     */
    private $block;

    /**
     * @var Item Item concerned
     */
    private $item;

    /**
     * @var \SplInt Number of times the item should be purchased.
     *          Default: 0
     */
    private $count = 0;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    private function setId($id)
    {
        $this->id = $id;
    }


    /**
     * @return Block
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * @param Block $block
     */
    public function setBlock($block)
    {
        $this->block = $block;
    }

    /**
     * @return Item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param Item $item
     *
     * @return ItemBlock
     */
    public function setItem(Item $item)
    {
        $this->item = $item;
    }

    /**
     * @return \SplInt
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param \SplInt $count
     *
     * @return ItemBlock
     */
    public function setCount(\SplInt $count)
    {
        $this->count = $count;
    }

    /**
     * Convert the item block as an array
     * @return array
     */
    public function toArray()
    {
        $array = [];
        $array['id']    = (string) $this->getItem()->get('id');
        $array['count'] = (int) $this->getCount();
        return $array;
    }

    /**
     * @inheritDoc
     */
    public function save()
    {
        if($this->id != null){
            $this->update();
        }else{
            $this->insert();
        }
    }


    private function update()
    {
        $pdo = DatabaseManager::getConnection();
        $stmt = $pdo->prepare("UPDATE itemblock SET  id = :id, item = :item, count = :count, id_block = :id_block");
        $stmt->bindParam(':id', $this->id,\PDO::PARAM_INT);
        $this->bindParam($stmt);
        $stmt->execute();
    }

    private function insert()
    {
        $pdo = DatabaseManager::getConnection();
        $stmt = $pdo->prepare("INSERT INTO itemblock (item, count, id_block) VALUES (:item, :count, :id_block)");
        $this->bindParam($stmt);
        $stmt->execute();
        $this->id = $pdo->lastInsertId();
    }

    /**
     * Bind all params except ID, as it can be null
     * @param \PDOStatement $stmt
     */
    private function bindParam(\PDOStatement $stmt)
    {
        $item = $this->item->get('id');
        $id_block = $this->getBlock()->getId();
        $stmt->bindParam(':item',     $item,        \PDO::PARAM_INT );
        $stmt->bindParam(':count',    $this->count, \PDO::PARAM_INT );
        $stmt->bindParam(':id_block', $id_block,    \PDO::PARAM_INT );
    }

    /**
     * @inheritDoc
     */
    public function delete()
    {
        if ($this->id != null) {
            $pdo = DatabaseManager::getConnection();
            $stmt = $pdo->prepare("DELETE FROM itemblock WHERE id = :id");
            $stmt->bindParam(':id', $this->id, \PDO::PARAM_INT);
            $stmt->execute();
        }
    }

    /**
     * Return the items of an given Block
     * @param Block $block
     *
     * @return ItemBlock[]
     */
    public static function findAllByBlock(Block $block)
    {
        $id =  $block->getId();

        $pdo = DatabaseManager::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM itemblock WHERE id_block = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $array = $stmt->fetchAll();
        if($array == false)
            return [];
        $items = [];
        $api = ApiManager::getAPI();
        foreach($array as $data){
            $item = new ItemBlock();
            $item->setId($data['id']);
            $item->setItem($api->staticData()->getItem($data['item'], 'all'));
            $item->setCount(new \SplInt((int) $data['count']));
            $item->setBlock($block);
            $items[] = $item;
        }
        return $items;
    }
}