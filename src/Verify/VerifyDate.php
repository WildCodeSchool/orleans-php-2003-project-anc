<?php


namespace App\Verify;

use DateTime;

class VerifyDate
{
    private $errorsDate = [];
    private $dates;

    public function __construct(array $dates)
    {
        $this->dates = $dates;
    }

    public function dateControl()
    {
        $this->dateValidity($this->dates);
        $this->dateChronology($this->dates);

        return $this->errorsDate;
    }

    private function dateChronology(array $dates): void
    {

        if ($dates['start'] < date("Y-m-d")) {
            $this->errorsDate['early_start_date'] =
                "la date de début de l'évènement ne peut être antérieure à la date du jour.";
        }

        if (!empty($dates['end'])) {
            if ($dates['end'] <= $dates['start']) {
                $this->errorsDate['end_date_before_start'] =
                    "La date de fin de l'évènement doit se situer au minimum 1 jour après la date de début.
                Si l'évènement se déroule sur une seule journée, saisir uniquement la date de début.";
            }
        }
    }

    private function dateValidity(array $dates): void
    {
        $test = new DateTime();
        $testStart = $test::createFromFormat("Y-m-d", $dates['start']);

        if ($testStart == false && array_sum($test::getLastErrors())) {
            $this->errorsDate['start_date_invalid'] = "Le format de la date de début de l'évènement est invalide.";
        }
        if (!empty($dates['end'])) {
            $testEnd = $test::createFromFormat("Y-m-d", $dates['end']);
            if ($testEnd == false && array_sum($test::getLastErrors())) {
                $this->errorsDate['end_date_invalid'] = "Le format de la date de fin de l'évènement est invalide.";
            }
        }
    }
}
