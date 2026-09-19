<?php

namespace App\Helpers;

use App\Models\EmailTemplate;

class EmailHelper
{
    public static function getProcessedTemplate($slug, $data = [])
    {
        $template = EmailTemplate::where('slug', $slug)->first();

        if (!$template) {
            return ['subject' => 'No Subject', 'body' => 'Template not found'];
        }

        $search  = array_map(fn($k) => '{' . $k . '}', array_keys($data));
        $replace = array_values($data);

        $subject = str_replace($search, $replace, $template->subject);
        $body    = str_replace($search, $replace, $template->body);

        return ['subject' => $subject, 'body' => $body];
    }
}