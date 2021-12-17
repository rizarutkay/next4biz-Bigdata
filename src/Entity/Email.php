<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EmailRepository;
use App\Entity\Trait\CreatedAtTrait;

/**
 * @ORM\Table("email", indexes={
 *  @ORM\Index(name="year_week_idx", columns={"year", "week"}),
 *  @ORM\Index(name="year_month_day_idx", columns={"year", "month", "day"}),
 * })
 * @ORM\Entity(repositoryClass=EmailRepository::class)
 */
class Email
{
    use CreatedAtTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $emailId;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private ?int $year = null;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private ?int $month = null;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private ?int $week = null;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private ?int $day = null;


    /**
     * @return integer
     */
    public function getEmailId(): int
    {
        return $this->emailId;
    }

    /**
     * @return integer|null
     */
    public function getYear(): ?int
    {
        return $this->year;
    }

    /**
     * @param integer $year
     * 
     * @return self
     */
    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return integer|null
     */
    public function getMonth(): ?int
    {
        return $this->month;
    }

    /**
     * @param integer $month
     * 
     * @return self
     */
    public function setMonth(int $month): self
    {
        $this->month = $month;

        return $this;
    }

    /**
     * @return integer|null
     */
    public function getWeek(): ?int
    {
        return $this->week;
    }

    /**
     * @param integer $week
     * 
     * @return self
     */
    public function setWeek(int $week): self
    {
        $this->week = $week;

        return $this;
    }

    /**
     * @return integer|null
     */ 
    public function getDay(): ?int
    {
        return $this->day;
    }

    /**
     * @param integer $day
     * 
     * @return self
     */
    public function setDay(int $day): self
    {
        $this->day = $day;

        return $this;
    }
}
