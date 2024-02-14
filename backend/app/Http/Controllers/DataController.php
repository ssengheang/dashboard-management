<?php

namespace App\Http\Controllers;

use App\Models\YourModel; // Replace YourModel with the actual model you're updating

class DataController extends Controller
{
    public function updateData($id)
    {
        // Replace 'YOUR_API_TOKEN' with the actual API token you obtained from BotFather
        $apiToken = 'YOUR_API_TOKEN';
        $chatId = 'YOUR_CHAT_ID'; // Replace with the actual chat ID where you want to send alerts

        // Fetch the existing data from the database
        // $data = YourModel::find($id);

        // if (!$data) {
        //     return response()->json(['error' => 'Data not found.']);
        // }

        // $data->update([
        //     'field1' => 'new_value1',
        //     'field2' => 'new_value2',
        // ]);

        $telegramMessage = "Data with ID {$id} has been updated.";
        $this->sendTelegramAlert($apiToken, $chatId, $telegramMessage);

        return response()->json(['message' => 'Data updated successfully.']);
    }

    private function sendTelegramAlert($apiToken, $chatId, $message)
    {
        $telegramEndpoint = "https://api.telegram.org/bot{$apiToken}/sendMessage";
        $params = [
            'chat_id' => $chatId,
            'text' => $message,
        ];

        $client = new \GuzzleHttp\Client();
        $client->request('POST', $telegramEndpoint, ['form_params' => $params]);
    }
}
