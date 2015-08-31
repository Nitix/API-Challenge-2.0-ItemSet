<?php


namespace Nitixx\Models;
use LeagueWrap\Dto\StaticData\SummonerSpell;
use Nitixx\Controllers\ApiManager;
use Nitixx\Controllers\DatabaseManager;

/**
 * Class Block
 * @package Models
 *          Represent a block within an item set
 */
class Block implements DBObjectInterface
{

    /**
     * @var int ID of the block, used for the database
     */
    private $id;

    /**
     * @var ItemSet Item set of the current Block
     */
    private $itemSet;

    /**
     * @var string name of the block
     */
    private $type = "Block";

    /**
     * @var \SplBool indicate if the block must be displayed as the tutorial formatting
     *              Default : false
     */
    private $recMath = false;

    /**
     * @var \SplInt minimum level of the account for the block to be displayed
     *          Default : -1 ( any account level)
     */
    private $minSummonerLevel = - 1;

    /**
     * @var int maximum level of the account for the block to be displayed
     *          Default : -1 ( any account level)
     */
    private $maxSummonerLevel = -1;

    /**
     * @var SummonerSpell Display the block if the player has equipped a specific summoner spell.
     *                    Does not override hideIfSummonerSpell
     *                    If the value is null, an empty string will be displayed when converting to json
     */
    private $showIfSummonerSpell;

    /**
     * @var SummonerSpell Hide the block if the player has equipped a specific summoner spell.
     *                    Override hideIfSummonerSpell
     *                    If the value is null, an empty string will be displayed when converting to json
     */
    private $hideIfSummonerSpell;

    /**
     * @var ItemBlock[] List of items in the block
     */
    private $items = [];

    /**
     * @var string Comment of the block. It would be useful if in game, we can see this comment
     */
    private $comment = "";

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
     * @return ItemSet
     */
    public function getItemSet()
    {
        return $this->itemSet;
    }

    /**
     * @param ItemSet $itemSet
     */
    public function setItemSet($itemSet)
    {
        $this->itemSet = $itemSet;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return \SplBool
     */
    public function getRecMath()
    {
        return $this->recMath;
    }

    /**
     * @param \SplBool $recMath
     */
    public function setRecMath(\SplBool $recMath)
    {
        $this->recMath = $recMath;
    }

    /**
     * @return \SplInt
     */
    public function getMinSummonerLevel()
    {
        return $this->minSummonerLevel;
    }

    /**
     * @param \SplInt $minSummonerLevel
     */
    public function setMinSummonerLevel(\SplInt $minSummonerLevel)
    {
        $this->minSummonerLevel = $minSummonerLevel;
    }

    /**
     * @return \SplInt
     */
    public function getMaxSummonerLevel()
    {
        return $this->maxSummonerLevel;
    }

    /**
     * @param \SplInt $maxSummonerLevel
     */
    public function setMaxSummonerLevel(\SplInt $maxSummonerLevel)
    {
        $this->maxSummonerLevel = $maxSummonerLevel;
    }

    /**
     * @return SummonerSpell
     */
    public function getShowIfSummonerSpell()
    {
        return $this->showIfSummonerSpell;
    }

    /**
     * @param SummonerSpell $showIfSummonerSpell
     */
    public function setShowIfSummonerSpell(SummonerSpell $showIfSummonerSpell)
    {
        $this->showIfSummonerSpell = $showIfSummonerSpell;
    }

    /**
     * @return SummonerSpell
     */
    public function getHideIfSummonerSpell()
    {
        return $this->hideIfSummonerSpell;
    }

    /**
     * @param SummonerSpell $hideIfSummonerSpell
     */
    public function setHideIfSummonerSpell(SummonerSpell $hideIfSummonerSpell)
    {
        $this->hideIfSummonerSpell = $hideIfSummonerSpell;
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
     * @return ItemBlock[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param ItemBlock[] $items
     */
    public function setItems(array $items)
    {
        $this->items = $items;
    }

    /**
     * Add an item to the current block
     * @param ItemBlock $itemBlock ItemBlock to add
     */
    public function addItem(ItemBlock $itemBlock)
    {
        $this->items[] = $itemBlock;
    }

    /**
     * Remove an ItemBlock from the current Block
     *
     * @param ItemBlock $itemBlock
     */
    public function removeBlock(ItemBlock $itemBlock)
    {
        $itemBlock->delete();
        $index = array_search($itemBlock, $this->items);

        if ($index !== false)
            unset($this->items[$index]);
    }


    /**
     * Convert the current Block as an array
     * @return array
     */
    public function toArray()
    {
        $array = [];
        $array['type']                = (string) $this->getType();
        $array['recMath']             = (bool) $this->getRecMath();
        $array['minSummonerLevel']    = (int) $this->getMinSummonerLevel();
        $array['maxSummonerLevel']    = (int) $this->getMaxSummonerLevel();
        if(!empty($this->getShowIfSummonerSpell()))
            $array['showIfSummonerSpell'] = $this->getShowIfSummonerSpell()->get('key');
        if(!empty($this->getHideIfSummonerSpell()))
            $array['hideIfSummonerSpell'] = $this->getHideIfSummonerSpell()->get('key');
        $array['items']               = [];
        foreach($this->getItems() as $item){
            $array['items'][] = $item->toArray();
        }
        return $array;
    }

    /**
     * Save the current object to the database, and save all itemBlocks
     * @throws \PDOException
     */
    public function save()
    {
        if($this->id != null){
            $this->update();
        }else{
            $this->insert();
        }
        foreach($this->items as $item){
            $item->save();
        }
    }

    private function update()
    {
        $pdo = DatabaseManager::getConnection();
        $stmt = $pdo->prepare('UPDATE block SET  id = :id, type = :type, recMath = :recMath, minSummonerLevel = :minSummonerLevel, maxSummonerLevel = :maxSummonerLevel,
          showIfSummonerSpell = :showIfSummonerSpell, hideIfSummonerSpell = :hideIfSummonerSpell, id_itemset = :id_itemset, comment = :comment');
        $stmt->bindParam(':id', $this->id,\PDO::PARAM_INT);
        $this->bindParam($stmt);
        $stmt->execute();
    }

    private function insert()
    {
        $pdo = DatabaseManager::getConnection();
        $stmt = $pdo->prepare('INSERT INTO block (type, recMath, minSummonerLevel, maxSummonerLevel, showIfSummonerSpell, hideIfSummonerSpell, id_itemset, comment)
        VALUES (:type, :recMath, :minSummonerLevel, :maxSummonerLevel, :showIfSummonerSpell, :hideIfSummonerSpell,:id_itemset, :comment)');
        $this->bindParam($stmt);
        $stmt->execute();
        $this->id = $pdo->lastInsertId();
    }

    private function bindParam(\PDOStatement $stmt)
    {
        if(!empty($this->showIfSummonerSpell)) {
            $show = $this->showIfSummonerSpell->get('id');
        }else{
            $show = '';
        }
        if(!empty($this->hideIfSummonerSpell)) {
            $hide = $this->hideIfSummonerSpell->get('id');
        }else{
            $hide = '';
        }

        $id_itemset = $this->itemSet->getId();
        $type = $this->type;
        $comment = $this->comment;
        $stmt->bindParam(':type',                $type,                   \PDO::PARAM_STR );
        $stmt->bindParam(':recMath',             $this->recMath,          \PDO::PARAM_BOOL );
        $stmt->bindParam(':minSummonerLevel',    $this->minSummonerLevel, \PDO::PARAM_INT );
        $stmt->bindParam(':maxSummonerLevel',    $this->maxSummonerLevel, \PDO::PARAM_INT );
        $stmt->bindParam(':showIfSummonerSpell', $show,                   \PDO::PARAM_INT );
        $stmt->bindParam(':hideIfSummonerSpell', $hide,                   \PDO::PARAM_INT );
        $stmt->bindParam(':comment',             $comment,                \PDO::PARAM_STR );
        $stmt->bindParam(':id_itemset',          $id_itemset,             \PDO::PARAM_INT );
    }

    /**
     * @inheritDoc
     */
    public function delete()
    {
        if ($this->id != null) {
            $pdo = DatabaseManager::getConnection();
            $stmt = $pdo->prepare("DELETE FROM block WHERE id = :id");
            $stmt->bindParam(':id', $this->id, \PDO::PARAM_INT);
            $stmt->execute();
        }
    }

    /**
     * Return the list of Blocks of a given itemset
     * @param ItemSet $itemSet
     *
     * @return Block[]
     */
    public static function findAllByItemSet(ItemSet $itemSet){
        $id = $itemSet->getId();

        $pdo = DatabaseManager::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM block WHERE id_itemset = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $array = $stmt->fetchAll();
        if($array == false)
            return [];
        $blocks = [];
        foreach($array as $data) {
            $block = new Block();
            $block->setId($data['id']);
            $block->setType($data['type']);
            $block->setRecMath(new \SplBool($data['recMath'] == 1 ? true : false));
            $block->setMinSummonerLevel(new \SplInt((int)$data['minSummonerLevel']));
            $block->setMaxSummonerLevel(new \SplInt((int)$data['maxSummonerLevel']));
            $api = ApiManager::getAPI();
            if ($data['showIfSummonerSpell'] != 0) {
                $block->setShowIfSummonerSpell($api->staticData()->getSummonerSpell($data['showIfSummonerSpell'], 'all'));
            }
            if ($data['hideIfSummonerSpell'] != 0) {
                $block->setHideIfSummonerSpell($api->staticData()->getSummonerSpell($data['hideIfSummonerSpell'], 'all'));
            }
            $block->setComment($data['comment']);
            $block->setItemSet($itemSet);
            $block->setItems(ItemBlock::findAllByBlock($block));
            $blocks[] = $block;
        }
        return $blocks;
    }
}