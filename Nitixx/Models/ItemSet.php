<?php


namespace Nitixx\Models;
use LeagueWrap\Dto\StaticData\Champion;
use Nitixx\Controllers\ApiManager;
use Nitixx\Controllers\DatabaseManager;

/**
 * Class ItemSet
 * @package Models
 */

class ItemSet implements DBObjectInterface
{

    /**
     * @var int ID of the block, used for the database
     */
    private $id;

    /**
     * @var string Name of the item set
     *             Displayed in the game
     */
    private $title = "Item Set";

    /**
     * @var ItemSetType Type of item set
     *                  custom will be displayed first
     *                  default = custom
     */
    private $type = ItemSetType::CUSTOM;

    /**
     * @var Map The map were the item set will appear on
     *          Possible value : ANY, SR, HA, TT, CS
     *          Default : ANY
     */
    private $map = Map::ANY;

    /**
     * @var Mode The mode were the item set will appear on
     *           Possible value : ANY, CLASSIC, ODIN, ARAM
     *           Default : ANY
     */
    private $mode = Mode::ANY;

    /**
     * @var bool Indicate if the item set as the priority over others item sets
     *              Override sortrank but not type
     *              Default : false
     */
    private $priority = false;

    /**
     * @var int The order in which this item set will be sorted within a specific type.
     *          Item sets are sorted in descending order.
     */
    private $sortrank = 0;

    /**
     * @var Block[] List of blocks of the item set
     */
    private $blocks;

    /**
     * @var string Comment of the itemset
     */
    private $comment = "";

    /**
     * @var Champion
     */
    private $champion = 0;

    /**
     * @param int $id
     */
    private function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle( $title)
    {
        $this->title = $title;
    }

    /**
     * @return ItemSetType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param ItemSetType $type
     */
    public function setType(ItemSetType $type)
    {
        $this->type = $type;
    }

    /**
     * @return Map
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * @param Map $map
     */
    public function setMap(Map $map)
    {
        $this->map = $map;
    }

    /**
     * @return Mode
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param Mode $mode
     */
    public function setMode(Mode $mode)
    {
        $this->mode = $mode;
    }

    /**
     * @return bool
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param \SplBool $priority
     */
    public function setPriority(\SplBool $priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return int
     */
    public function getSortrank()
    {
        return $this->sortrank;
    }

    /**
     * @param \SplInt $sortrank
     */
    public function setSortrank(\SplInt $sortrank)
    {
        $this->sortrank = $sortrank;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return Champion
     */
    public function getChampion()
    {
        return $this->champion;
    }

    /**
     * @param Champion $champion
     */
    public function setChampion(Champion $champion)
    {
        $this->champion = $champion;
    }

    /**
     * @return Block[]
     */
    public function getBlocks()
    {
        if($this->blocks == null){
            if($this->id != null){
                $this->setBlocks(Block::findAllByItemSet($this));
            }else{
                $this->blocks = [];
            }
        }
        return $this->blocks;
    }

    /**
     * @param Block[] $blocks
     */
    public function setBlocks(array $blocks)
    {
        $this->blocks = $blocks;
    }

    /**
     * Add an block to the current Item Set
     * @param Block $block Block to add
     */
    public function addBlock(Block $block)
    {
        if($this->blocks == null){
            if($this->id != null){
                $this->setBlocks(Block::findAllByItemSet($this));
            }else{
                $this->blocks = [];
            }
        }
        $this->blocks[] = $block;
    }

    /**
     * Remove an block from the current Item Set
     * @param Block $block
     */
    public function removeBlock(Block $block)
    {
        if($this->blocks == null){
            if($this->id != null){
                $this->setBlocks(Block::findAllByItemSet($this));
            }else{
                $this->blocks = [];
            }
        }
        $block->delete();
        $index = array_search($block, $this->blocks);

        if ($index !== false)
            unset($this->blocks[$index]);
    }

    /**
     * Convert the itemSet as an array
     * @return array The Item as an array
     */
    public function toArray()
    {
        $array = [];
        $array['title']    = (string) $this->getTitle();
        $array['type']     = (string) $this->getType();
        $array['map']      = (string) $this->getMap();
        $array['mode']     = (string) $this->getMode();
        $array['priority'] = (bool) $this->getPriority();
        $array['sortrank'] = (int) $this->getSortrank();
        $array['blocks']   = [];
        foreach($this->getBlocks() as $block){
            $array['blocks'][] = $block->toArray();
        }
        return $array;
    }

    /**
     * Return the item set as an json string
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * Save the current object to the database, and save all blocks
     * @throws \PDOException
     */
    public function save()
    {
        if($this->id != null){
            $this->update();
        }else{
            $this->insert();
        }
        foreach($this->blocks as $block){
            $block->save();
        }
    }

    private function update()
    {
        $pdo = DatabaseManager::getConnection();
        $stmt = $pdo->prepare("UPDATE itemset SET  id = :id, title = :title, type = :type, map = :map, mode = :mode, priority = :priority, sortrank = :sortrank, comment = :comment, champion = :champion ");
        $stmt->bindParam(':id', $this->id,\PDO::PARAM_INT);
        $this->bindParam($stmt);
        $stmt->execute();
    }

    private function insert()
    {
        $pdo = DatabaseManager::getConnection();
        $stmt = $pdo->prepare("INSERT INTO itemset (title, type, map, mode, priority, sortrank, comment, champion) VALUES (:title, :type, :map, :mode, :priority, :sortrank, :comment, :champion)");
        $this->bindParam($stmt);
        $stmt->execute();
        $this->id = $pdo->lastInsertId();
    }

    private function bindParam(\PDOStatement $stmt)
    {
        $map  = $this->map; //PDO will overwrite my datatype if I don't copy them
        $type = $this->type;
        $mode = $this->mode;
        $champion = $this->champion->get('id');
        $stmt->bindParam(':title',    $this->title,    \PDO::PARAM_STR );
        $stmt->bindParam(':type',     $type,           \PDO::PARAM_STR );
        $stmt->bindParam(':map',      $map,            \PDO::PARAM_STR );
        $stmt->bindParam(':mode',     $mode,           \PDO::PARAM_STR );
        $stmt->bindParam(':priority', $this->priority, \PDO::PARAM_BOOL );
        $stmt->bindParam(':sortrank', $this->sortrank, \PDO::PARAM_INT );
        $stmt->bindParam(':comment',  $this->comment,  \PDO::PARAM_STR );
        $stmt->bindParam(':champion', $champion,       \PDO::PARAM_INT );
    }

    /**
     * @inheritDoc
     */
    public function delete()
    {
        if ($this->id != null) {
            $pdo = DatabaseManager::getConnection();
            $stmt = $pdo->prepare("DELETE FROM itemset WHERE id = :id");
            $stmt->bindParam(':id', $this->id, \PDO::PARAM_INT);
            $stmt->execute();
        }
    }

    /**
     * Give the itemSet by an given id
     * @param $id ID of the item set
     *
     * @return ItemSet|null
     */
    public static function findById($id)
    {
        $pdo = DatabaseManager::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM itemset WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch();
        if($data == false)
            return null;
        $itemSet = new ItemSet();
        $itemSet->parseArray($data);
        return $itemSet;
    }

    /**
     * Give the list of item set
     * @return ItemSet[] list of ItemSet
     */
    public static function findAll()
    {
        $array = [];
        $pdo = DatabaseManager::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM itemset ORDER BY id DESC ");
        $stmt->execute();
        while($data = $stmt->fetch()){
            $itemSet = new ItemSet();
            $itemSet->parseArray($data);
            $array[] = $itemSet;
        }
        return $array;
    }

    /**
     * Parse the array to add data to the current ItemSet
     * This is used to parse data from database
     * @param array $data
     */
    private function parseArray(array $data)
    {
        $this->setId($data['id']);
        $this->setTitle($data['title']);
        $this->setType(ItemSetType::getItemSetType($data['type']));
        $this->setMap(Map::getMap($data['map']));
        $this->setMode(Mode::getMode($data['mode']));
        $this->setPriority(new \SplBool($data['priority'] == 1 ? true : false));
        $this->setSortrank(new \SplInt((int)$data['sortrank']));
        $this->setComment($data['comment']);
        if($data['champion'] != 0) {
            $this->setChampion(ApiManager::getAPI()->staticData()->getChampion($data['champion'], 'all'));
        }
        //No blocks as they are loading when needed
    }
}