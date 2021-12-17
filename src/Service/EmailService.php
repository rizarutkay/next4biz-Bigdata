<?php

namespace App\Service;

use App\Repository\EmailRepository;

class EmailService
{
    /**
     * @param EmailRepository $emailRepository
     */
    public function __construct(EmailRepository $emailRepository)
    {
        $this->emailRepository = $emailRepository;
    }

    /**
     * @param string $startDate
     * @param string $endDate
     * 
     * @return array|null
     */
    public function getYearlyEmailReport(string $startDate, string $endDate): ?array
    {
        $startYear = date('Y', strtotime($startDate));
        $endYear = date('Y', strtotime($endDate));

        $emailReports = $this->emailRepository->getYearlyEmailCount($startYear, $endYear);

        $emailReportsResult = null;

        foreach ($emailReports as $emailReport) {
            $emailReportsResult[] = [
                'e-mail' => $emailReport['email'],
                'date' => (string) $emailReport['year'],
            ];
        }

        return $emailReportsResult;
    }

    /**
     * @param string $startDate
     * @param string $endDate
     * 
     * @return array|null
     */
    public function getMonthlyEmailReport(string $startDate, string $endDate): ?array
    {
        $startDate = date('Y-m', strtotime($startDate));
        $endDate = date('Y-m', strtotime($endDate));

        while ($startDate<=$endDate) {
            $currentYear = date('Y', strtotime($startDate));
            $queryDates[$currentYear][] = date('m', strtotime($startDate));
            $startDate = date('Y-m', strtotime($startDate. '+1 month'));
        }

        $emailReports = $this->emailRepository->getMonthlyEmailCount($queryDates);

        $emailReportsResult = null;

        foreach ($emailReports as $emailReport) {
            $emailReportsResult[] = [
                'e-mail' => $emailReport['email'],
                'date' => date('Y M', strtotime($emailReport['date'])),
            ];
        }

        return $emailReportsResult;
    }

    /**
     * @param string $startDate
     * @param string $endDate
     * 
     * @return array|null
     */
    public function getWeeklyEmailReport(string $startDate, string $endDate): ?array
    {
        while ($startDate<=$endDate) {
            $currentYear = date('Y', strtotime($startDate));
            $queryDates[$currentYear][] = date('W', strtotime($startDate));
            $startDate = date('Y-m-d', strtotime($startDate. '+1 week'));
        }

        $emailReports = $this->emailRepository->getWeeklyEmailCount($queryDates);

        $emailReportsResult = null;

        foreach ($emailReports as $emailReport) {
            $emailReportsResult[] = [
                'e-mail' => $emailReport['email'],
                'date' => date('Y M', strtotime($emailReport['date'])),
            ];
        }

        return $emailReportsResult;
    }

    /**
     * @param string $startDate
     * @param string $endDate
     * 
     * @return array|null
     */
    public function getDailyEmailReport(string $startDate, string $endDate): ?array
    {
        while ($startDate<=$endDate) {
            $currentYear = date('Y', strtotime($startDate));
            $currentMonth = date('m', strtotime($startDate));
            $queryDates[$currentYear][$currentMonth][] = date('d', strtotime($startDate));
            $startDate = date('Y-m-d', strtotime($startDate. '+1 day'));
        }

        $emailReports = $this->emailRepository->getDailyEmailCount($queryDates);

        $emailReportsResult = null;

        foreach ($emailReports as $emailReport) { 
            $emailReportsResult[] = [
                'e-mail' => $emailReport['email'],
                'date' => date('Y M', strtotime($emailReport['date'])),
            ];
        }

        return $emailReportsResult;
    }
}