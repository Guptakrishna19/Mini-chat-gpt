<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Welcome extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->library('form_validation');

        // Load database
        $this->load->database();
    }
    public function index()
	{
		$this->load->view('welcome_message');
	}
    public function generate()
    {
        $query = $this->input->post('prompt'); // Get the prompt from the POST request
        // Set your Gemini API key
        $apiKey = 'AIzaSyCtX_HPUKESQ5R8GQwVcJV2TceVriebK6g'; // Replace this with your actual API Key
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $apiKey;
        // Prepare the POST data (json format)
        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' =>$query]
                    ]
                ]
            ]
        ];
        // cURL request to Gemini API
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Execute cURL and get the response
        $response = curl_exec($ch);
        // Check for any errors
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        } else {
            $responseData = json_decode($response, true);
            // Check if responseData has the expected structure
            if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
                $responseText = $responseData['candidates'][0]['content']['parts'][0]['text'];
                // Return the SQL query in the response
                echo json_encode(['status' => 'success', 'query' => $responseText]);
            } else {
                // Handle case where the expected data doesn't exist
                echo json_encode(['status' => 'error', 'message' => 'Response is Invalid incorrect']);
            }
        }
        // Close cURL
        curl_close($ch);
    }
}

