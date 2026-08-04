<?php

namespace App\Classes;

use App\Models\Config;
use Illuminate\Support\Facades\Log;
use Orhanerday\OpenAi\OpenAi;
use Illuminate\Support\Facades\Storage;

class AiImages
{
    public function generate($request)
    {
        // Query
        $config = Config::get();

        // Parameters
        $open_ai_key = $config[35]->config_value;
        $open_ai = new OpenAi($open_ai_key);
        $size = $request->size;
        $results = (int)$request->results;

        // Parameters
        $complete = $open_ai->image([
            "model" => $config[46]->config_value, // gpt-image-1.5
            "prompt" => "Create " . $request->name . " image using " . $request->style,
            "n" => $results,
            "size" => $size,
            // no response_format — gpt-image-1.x always returns b64_json
        ]);

        // Result
        $result = json_decode($complete);

        $savedImagePaths = array();

        // Save the generated image to the public storage
        if ($result && isset($result->data)) {
            foreach ($result->data as $imageData) {
                // gpt-image-1.x returns base64 in b64_json, not a url
                if (!isset($imageData->b64_json)) {
                    continue;
                }

                $imageContent = base64_decode($imageData->b64_json);

                // Generate a unique filename for the image
                $filename = uniqid() . '-' . time() . '.png';

                // Save the image to the public storage using public_path
                $filePath = public_path("images/generate-images/{$filename}");
                file_put_contents($filePath, $imageContent);

                // Store the path in the array
                $savedImagePaths[] = "images/generate-images/{$filename}";
            }
        }

        // Check error
        if (isset($result->error)) {
            $this->result = $result->error;
        } else {
            $this->result = $savedImagePaths;
        }

        return $this->result;
    }
}
