<?php

class Element
{
    private string $name;
    private array $weakness = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setWeaknesses(array $elements)
    {
        foreach ($elements as $element){
            if($element instanceof Element) {
                $this->weakness = $elements;
            }
        }
    }

    public function isWeakAgainst(Element $element)
    {
        return in_array($element, $this->weakness);
    }
}

class Player
{
    private string $name;
    private int $score = 0;
    private bool $CPU;
    private Element $selection;

    public function __construct(string $name, bool $CPU = true)
    {
        $this->name = $name;
        $this->CPU = $CPU;
    }

    public function setName()
    {
        return $this->name;
    }

    public function isCPU()
    {
       return $this->CPU;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function setScore(): void
    {
        $this->score = $this->score + 1;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setSelection(Element $selection): void
    {
        $this->selection = $selection;
    }

    public function getSelection(): Element
    {
        return $this->selection;
    }

}

class Game
{
    /** @var Element[] */
    private array $elements = [];

    public function __construct()
    {
        $this->setUp();
    }

    public function setUp()
    {
        $this->elements = [
            1 => $rock = new Element("rock"),
            2 =>  $paper = new Element("paper"),
            3 => $scissors = new Element("scissors"),
        ];

        $rock->setWeaknesses([$paper]);
        $paper->setWeaknesses([$scissors]);
        $scissors->setWeaknesses([$rock]);
    }


    public function play()
    {

        $player1 = new Player(readline("Set player1 name: "));
        $player2 = new Player(readline("Set player2 name: "));


        $this->showElements();

        $player1->setSelection($this->elements[(int)readline($player1->getName() . " Select element ")]);
        $player2->setSelection($this->elements[(int)readline($player2->getName() . " Select element ")]);

        if ($player1->getSelection() === $player2->getSelection()) {
            echo "TIE!" . PHP_EOL;
        }
        if ($player1->getSelection()->isWeakAgainst($player2->getSelection())) {
            $player2->setScore();
            echo $player2->getName() . " WIN! Score: " . $player2->getScore() . PHP_EOL;
        } if ($player2->getSelection()->isWeakAgainst($player1->getSelection())){
        $player1->setScore();
        echo $player1->getName() . " WIN! Score: " . $player1->getScore() . PHP_EOL;
    }

    }

    public function showElements(): void
    {
        foreach ($this->elements as $key => $element){
            echo "[$key] : {$element->getName()}" . PHP_EOL;
        }
    }

}


class Tournament
{
    /** @var Element[] */
    private array $elements = [];
    /** @var Player *///
    // private int $score;
    private const MAX_NUM = 2;
    private Player $player1;
    private Player $player2;

    private array $winner = [];

    public function __construct()
    {
        $this->setUp();
    }

    public function setUp()
    {
        $this->elements = [
            1 => $rock = new Element("rock"),
            2 =>  $paper = new Element("paper"),
            3 => $scissors = new Element("scissors"),
        ];

        $rock->setWeaknesses([$paper]);
        $paper->setWeaknesses([$scissors]);
        $scissors->setWeaknesses([$rock]);
    }


    public function play(Player $player1, Player $player2)
    {

        $this->showElements();
        $this->winner = [];
        $this->loser = [];

        while (empty($this->winner)) {
            if($player1->isCPU() === false){
                $player1->setSelection($this->elements[(int)readline($player1->getName() . " Select element ")]);
            }
            if($player2->isCPU() === false){
                $player2->setSelection($this->elements[(int)readline($player1->getName() . " Select element ")]);
            }

            $player1->setSelection($this->elements[array_rand($this->elements)]);
            $player2->setSelection($this->elements[array_rand($this->elements)]);

            if ($player1->getSelection() === $player2->getSelection()) {
                echo "TIE!" . PHP_EOL;
                continue;
            }
            if ($player1->getSelection()->isWeakAgainst($player2->getSelection())) {
                $player2->setScore();
                echo $player2->getName() . " WIN! Score: " . $player2->getScore() . PHP_EOL;
            } else {
                $player1->setScore();
                echo $player1->getName() . " WIN! Score: " . $player1->getScore() . PHP_EOL;
            }
            if ($player1->getScore() == self::MAX_NUM) {
                $this->winner[] = $player1;
                $this->loser[] = $player2;
            }
            if ($player2->getScore() == self::MAX_NUM) {
                $this->winner[] = $player2;
                $this->loser[] = $player1;
            }
        }
    }

    public function showElements(): void
    {
        foreach ($this->elements as $key => $element){
            echo "[$key] : {$element->getName()}" . PHP_EOL;
        }
    }

    public function getWinner()
    {

        foreach ($this->winner as $winner){
            return $winner;
        }
    }

    public function getLoser()
    {
        foreach ($this->loser as $loser){
            return $loser;
        }
    }


}

$player1 = new Player(readline("Enter your name "), false);
$player2 = new Player("CPU1");
$player3 = new Player("CPU2");
$player4 = new Player("CPU3");
$player5 = new Player("CPU4");
$player6 = new Player("CPU5");
$player7 = new Player("CPU6");
$player8 = new Player("CPU7");


echo "----------------------- First round ----------------------\n";

$game1 = new Tournament();
$game1->play($player1, $player2);

$game2 = new Tournament();
$game2->play($player3, $player4);

$game3 = new Tournament();
$game3->play($player5, $player6);

$game4 = new Tournament();
$game4->play($player7, $player8);

echo "----------------------- Semi final ----------------------\n";

$game5 = new Tournament();
$game5->play($game1->getWinner(), $game2->getWinner());

$game6 = new Tournament();
$game6->play($game3->getWinner(), $game4->getWinner());

echo "----------------------- Final ----------------------\n";
$game7 = new Tournament();
$game7->play($game5->getWinner(), $game6->getWinner());

echo "winner is: " . $game7->getWinner()->getName() . PHP_EOL;

echo "----------------------- Result table ----------------------\n";

echo "8th - {$game1->getLoser()->getName()}" . PHP_EOL;
echo "7th - {$game2->getLoser()->getName()}" . PHP_EOL;
echo "6th - {$game3->getLoser()->getName()}" . PHP_EOL;
echo "5th - {$game4->getLoser()->getName()}" . PHP_EOL;
echo "4th - {$game5->getLoser()->getName()}" . PHP_EOL;
echo "3rd - {$game6->getLoser()->getName()}" . PHP_EOL;
echo "2nd - {$game7->getLoser()->getName()}" . PHP_EOL;
echo "1st - {$game7->getWinner()->getName()}" . PHP_EOL;
