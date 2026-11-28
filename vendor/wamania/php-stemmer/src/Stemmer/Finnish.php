<?php
/**
 * Finnish Snowball Stemmer.
 *
 * @author msaari <mikko@mikkosaari.fi>
 */
namespace Wamania\Snowball\Stemmer;

use Joomla\String\StringHelper;

/**
 * Finnish Snowball Stemmer.
 *
 * @link http://snowball.tartarus.org/algorithms/finnish/stemmer.html
 * @author msaari
 */
class Finnish extends Stem
{
    /**
     * All swedish vowels
     */
    protected static $vowels = array('a', 'e', 'i', 'o', 'u', 'y', 'ä', 'ö');

    protected static $consonants = array('b', 'c', 'd', 'f', 'g', 'h', 'j',
    'k', 'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'v', 'w', 'x', 'z');

    protected static $restrictedVowels = array('a', 'e', 'i', 'o', 'u', 'ä', 'ö');

    /**
     * Long restricted vowels, ie. doubled vowels.
     */
    protected static $longVowels = array('aa', 'ee', 'ii', 'oo', 'uu', 'ää', 'öö');

    private $_removedInStep3 = false;

    /**
     * {@inheritdoc}
     */
    public function stem($word)
    {
        $this->word = StringHelper::strtolower($word);

        // R1 and R2 are then defined in the usual way
        $this->r1();
        $this->r2();

        // Do each of steps 1, 2 3, 4, 5 and 6.

        $this->step1();
        $this->step2();
        $this->step3();
        $this->step4();
        $this->step5();
        $this->step6();

        return $this->word;
    }

    /**
     * Step 1
     *
     * Search for the longest among the following suffixes in R1, and perform
     * the action indicated.
     *
     * @return boolean True when something is done.
     */
    private function step1()
    {
        // (a) kin   kaan   kään   ko   kö   han   hän   pa   pä
        //      delete if preceded by n, t or a vowel
        if (($position = $this->searchIfInR1(array('kaan', 'kään', 'kin', 'han', 'hän', 'ko', 'kö', 'pa', 'pä'))) !== false) {
            $lastLetter = StringHelper::substr($this->word, ($position-1), 1);

            if (in_array($lastLetter, array_merge(['t', 'n'], self::$vowels))) {
                $this->word = StringHelper::substr($this->word, 0, $position);
                $this->r1();
                $this->r2();
            }

            return true;
        }

        //  sti
        //  delete if in R2
        if (($position = $this->searchIfInR1(array('sti'))) !== false) {
            if ($this->inR2($position)) {
                $this->word = StringHelper::substr($this->word, 0, $position);
                $this->r1();
                $this->r2();
            }

            return true;
        }
    }

    /**
     * Step 2: possessives.
     *
     * Search for the longest among the following suffixes in R1, and perform
     * the action indicated.
     *
     * @return boolean True when something is done.
     */
    private function step2()
    {
        // si
        //  delete if not preceded by k
        if (($position = $this->searchIfInR1(array('si'))) !== false) {
            $lastLetter = StringHelper::substr($this->word, ($position-1), 1);

            if ($lastLetter !== 'k') {
                $this->word = StringHelper::substr($this->word, 0, $position);
                $this->r1();
                $this->r2();
                return true;
            }
        }

        // ni
        //  delete
        if (($position = $this->searchIfInR1(array('ni'))) !== false) {
            $this->word = StringHelper::substr($this->word, 0, $position);
            // if preceded by kse, replace with ksi
            if ( ($position = $this->search(array('kse'))) !== false) {
                $this->word = preg_replace('#(kse)$#u', 'ksi', $this->word);
            }
            $this->r1();
            $this->r2();
            return true;
        }

        // nsa   nsä   mme   nne
        //  delete
        if (($position = $this->searchIfInR1(array('nsa', 'nsä', 'mme', 'nne'))) !== false) {
            $this->word = StringHelper::substr($this->word, 0, $position);
            $this->r1();
            $this->r2();
            return true;
        }

        // an
        //  delete if preceded by one of   ta   ssa   sta   lla   lta   na
        if (($position = $this->searchIfInR1(array('an'))) !== false) {
            $word = StringHelper::substr($this->word, 0, $position);
            $lastThreeLetters = StringHelper::substr($word, -3, 3);
            $lastTwoLetters = StringHelper::substr($word, -2, 2);
            if (in_array($lastThreeLetters, array('ssa', 'sta', 'lla', 'lta'), true) || in_array($lastTwoLetters, array('na', 'ta'), true)) {
                $this->word = $word;
                $this->r1();
                $this->r2();
                return true;
            }
        }

        // än
        // delete if preceded by one of   tä   ssä   stä   llä   ltä   nä
        if (($position = $this->searchIfInR1(array('än'))) !== false) {
            $word = StringHelper::substr($this->word, 0, $position);
            $lastThreeLetters = StringHelper::substr($word, -3, 3);
            $lastTwoLetters = StringHelper::substr($word, -2, 2);
            if (in_array($lastThreeLetters, array('ssä', 'stä', 'llä', 'ltä'), true) || in_array($lastTwoLetters, array('nä', 'tä'), true)) {
                $this->word = $word;
                $this->r1();
                $this->r2();
                return true;
            }
        }

        // en
        // delete if preceded by one of   lle   ine
        if (($position = $this->searchIfInR1(array('en'))) !== false) {
            $word = StringHelper::substr($this->word, 0, $position);
            if (StringHelper::strlen($this->word) > 4) {
                $lastThreeLetters = StringHelper::substr($this->word, -5, 3);
                if (in_array($lastThreeLetters, array('lle', 'ine'), true)) {
                    $this->word = $word;
                    $this->r1();
                    $this->r2();
                    return true;
                }
            }
        }
    }

    /**
     * Step 3: cases
     *
     * Search for the longest among the following suffixes in R1, and perform
     * the action indicated.
     *
     * @return boolean True when something is done.
     */
    private function step3()
    {
        // hXn
        // delete if preceded by X, where X is a V other than u (a/han, e/hen etc)
        foreach (self::$restrictedVowels as $vowel) {
            if ($vowel === 'u') {
                continue;
            }
            if (($position = $this->searchIfInR1(array('h' . $vowel . 'n'))) !== false) {
                $lastLetter = StringHelper::substr($this->word, $position-1, 1);
                if ($lastLetter === $vowel) {
                    $this->word = StringHelper::substr($this->word, 0, $position);
                    $this->_removedInStep3 = true;
                    $this->r1();
                    $this->r2();
                }
                return true;
            }
        }

        // siin   den   tten
        // delete if preceded by Vi
        if (($position = $this->searchIfInR1(array('siin', 'den', 'tten'))) !== false) {
            $lastLetter = StringHelper::substr($this->word, ($position-1), 1);
            if ($lastLetter === 'i') {
                $nextLastLetter = StringHelper::substr($this->word, ($position-2), 1);
                if (in_array($nextLastLetter, self::$restrictedVowels, true)) {
                    $this->word = StringHelper::substr($this->word, 0, $position);
                    $this->_removedInStep3 = true;
                    $this->r1();
                    $this->r2();
                    return true;
                }
            }
        }

        // seen
        // delete if preceded by LV
        if (($position = $this->searchIfInR1(array('seen'))) !== false) {
            $lastLetters = StringHelper::substr($this->word, ($position-2), 2);

            if (in_array($lastLetters, self::$longVowels, true)) {
                $this->word = StringHelper::substr($this->word, 0, $position);
                $this->_removedInStep3 = true;
                $this->r1();
                $this->r2();
                return true;
            }
        }

        // tta    ttä
        // delete if preceded by e
        if (($position = $this->searchIfInR1(array('tta', 'ttä'))) !== false) {
            $lastLetter = StringHelper::substr($this->word, ($position-1), 1);

            if ($lastLetter === 'e') {
                $this->word = StringHelper::substr($this->word, 0, $position);
                $this->_removedInStep3 = true;
                $this->r1();
                $this->r2();
                return true;
            }
        }

        // ta  tä  ssa  ssä  sta  stä  lla  llä  lta  ltä  lle  na  nä  ksi  ine
        // delete
        if (($position = $this->searchIfInR1(array('ssa', 'ssä', 'sta', 'stä', 'lla', 'llä', 'lta', 'ltä', 'lle', 'ksi', 'na', 'nä', 'ine', 'ta', 'tä'))) !== false) {
            $this->word = StringHelper::substr($this->word, 0, $position);
            $this->_removedInStep3 = true;
            $this->r1();
            $this->r2();
            return true;
        }

        // a    ä
        // delete if preceded by cv
        if (($position = $this->searchIfInR1(array('a', 'ä'))) !== false) {
            $lastLetter = StringHelper::substr($this->word, ($position-1), 1);
            $nextLastLetter = StringHelper::substr($this->word, ($position-2), 1);

            if (in_array($lastLetter, self::$vowels, true) && in_array($nextLastLetter, self::$consonants, true)) {
                $this->word = StringHelper::substr($this->word, 0, $position);
                $this->_removedInStep3 = true;
                $this->r1();
                $this->r2();
                return true;
            }
        }

        // n
        // delete, and if preceded by LV or ie, delete the last vowel
        if (($position = $this->searchIfInR1(array('n'))) !== false) {
            $lastLetters = StringHelper::substr($this->word, ($position-2), 2);

            if (in_array($lastLetters, self::$longVowels, true) || $lastLetters === 'ie') {
                $this->word = StringHelper::substr($this->word, 0, $position-1);
            } else {
                $this->word = StringHelper::substr($this->word, 0, $position);
            }
            $this->r1();
            $this->r2();
            $this->_removedInStep3 = true;
            return true;
        }
    }

    /**
     * Step 4: other endings
     *
     * Search for the longest among the following suffixes in R2, and perform
     * the action indicated
     *
     * @return boolean True when something is done.
     */
    private function step4()
    {
        // mpi   mpa   mpä   mmi   mma   mmä
        // delete if not preceded by po
        if (($position = $this->searchIfInR2(array('mpi', 'mpa', 'mpä', 'mmi', 'mma', 'mmä'))) !== false) {
            $lastLetters = StringHelper::substr($this->word, ($position-2), 2);
            if ($lastLetters !== 'po') {
                $this->word = StringHelper::substr($this->word, 0, $position);
                $this->r1();
                $this->r2();
                return true;
            }
        }

        // impi   impa   impä   immi   imma   immä   eja   ejä
        // delete
        if (($position = $this->searchIfInR2(array('impi', 'impa', 'impä', 'immi', 'imma', 'immä', 'eja', 'ejä'))) !== false) {
            $this->word = StringHelper::substr($this->word, 0, $position);
            $this->r1();
            $this->r2();
            return true;
        }
    }

    /**
     * Step 5: plurals
     * If an ending was removed in step 3, delete a final i or j if in R1;
     * otherwise,
     * if an ending was not removed in step 3, delete a final t in R1 if it
     * follows a vowel, and, if a t is removed, delete a final mma or imma in
     * R2, unless the mma is preceded by po.
     *
     * @return boolean True when something is done.
     */
    private function step5()
    {
        if ($this->_removedInStep3) {
            if (($position = $this->searchIfInR1(array('i', 'j'))) !== false) {
                $this->word = StringHelper::substr($this->word, 0, $position);
                $this->r1();
                $this->r2();
                return true;
            }
        } else {
            if (($position = $this->searchIfInR1(array('t'))) !== false) {
                $lastLetter = StringHelper::substr($this->word, ($position-1), 1);
                if (in_array($lastLetter, self::$vowels, true)) {
                    $this->word = StringHelper::substr($this->word, 0, $position);
                    $this->r1();
                    $this->r2();
                    if (($position2 = $this->searchIfInR2(array('imma'))) !== false) {
                        $this->word = StringHelper::substr($this->word, 0, $position2);
                        $this->r1();
                        $this->r2();
                        return true;
                    } elseif (($position2 = $this->searchIfInR2(array('mma'))) !== false) {
                        $lastLetters = StringHelper::substr($this->word, ($position2-2), 2);
                        if ($lastLetters !== 'po') {
                            $this->word = StringHelper::substr($this->word, 0, $position2);
                            $this->r1();
                            $this->r2();
                            return true;
                        }
                    }
                }
            }
        }

    }

    /**
     * Step 6: tidying up
     *
     * Do in turn steps (a), (b), (c), (d), restricting all tests to the
     * region R1.
     */
    private function step6()
    {
        // a) If R1 ends LV
        // delete the last letter
        if (($position = $this->searchIfInR1(self::$longVowels)) !== false) {
            $this->word = StringHelper::substr($this->word, 0, $position+1);
            $this->r1();
            $this->r2();
        }

        // b) If R1 ends cX, c a consonant and X one of   a   ä   e   i,
        // delete the last letter
        $lastLetter = StringHelper::substr($this->r1, -1, 1);
        $secondToLastLetter = StringHelper::substr($this->r1, -2, 1);
        if (in_array($secondToLastLetter, self::$consonants, true) && in_array($lastLetter, array('a', 'e', 'i', 'ä'))) {
            $this->word = StringHelper::substr($this->word, 0, -1);
            $this->r1();
            $this->r2();
        }

        // c) If R1 ends oj or uj
        // delete the last letter
        $twoLastLetters = StringHelper::substr($this->r1, -2, 2);
        if (in_array($twoLastLetters, array('oj', 'uj'))) {
            $this->word = StringHelper::substr($this->word, 0, -1);
            $this->r1();
            $this->r2();
        }

        // d) If R1 ends jo
        // delete the last letter
        $twoLastLetters = StringHelper::substr($this->r1, -2, 2);
        if ($twoLastLetters === 'jo') {
            $this->word = StringHelper::substr($this->word, 0, -1);
            $this->r1();
            $this->r2();
        }

        // e) If the word ends with a double consonant followed by zero or more
        // vowels, remove the last consonant (so eläkk -> eläk,
        // aatonaatto -> aatonaato)
        $endVowels = '';
        for ($i = StringHelper::strlen($this->word) - 1; $i > 0; $i--) {
            $letter = StringHelper::substr($this->word, $i, 1);
            if (in_array($letter, self::$vowels, true)) {
                $endVowels = $letter . $endVowels;
            } else {
                // check for double consonant
                $prevLetter = StringHelper::substr($this->word, $i-1, 1);
                if ($prevLetter === $letter) {
                    $this->word = StringHelper::substr($this->word, 0, $i) . $endVowels;
                }
                break;
            }
        }
    }
}
