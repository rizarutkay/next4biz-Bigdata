<?php

namespace App\Controller;

use App\Service\EmailService;
use App\Request\EmailRequestValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class EmailController extends AbstractController
{
    /**
     * @Route("/email/report", name="get_email_report")
     *
     * @param Request $request
     * @param EmailRequestValidator $emailRequestValidator
     * @param EmailService $emailService
     * 
     * @return JsonResponse
     */
    public function getEmailReport(Request $request, EmailRequestValidator $emailRequestValidator, EmailService $emailService): JsonResponse
    {        
        $data = json_decode($request->getContent(), true);

        $errors = $emailRequestValidator->validator->validate($data, $emailRequestValidator->rules());

        if(count($errors)>0) {
            foreach ($errors as $violation) {
                $messages[$violation->getPropertyPath()][] = $violation->getMessage();
           }

           return new JsonResponse([
            'message'=>$messages
        ]);
        }

        $startDate = $data['date_range']['start'];
        $endDate = $data['date_range']['end'];

        $emailReport = match($data['period']) {
            'daily' => $emailService->getDailyEmailReport($startDate, $endDate),
            'weekly' => $emailService->getWeeklyEmailReport($startDate, $endDate),
            'monthly' => $emailService->getMonthlyEmailReport($startDate, $endDate),
            'yearly' => $emailService->getYearlyEmailReport($startDate, $endDate),
            default => null
        };

        return new JsonResponse(
            $emailReport
        );
    }
}