<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;

// Sua chave de API da OpenWeatherMap
$apiKey = '493bcbb568f6897d096601831f16299b';

// Inicializa a variável de resposta
$responseData = [];

// Verifica se o parâmetro de cidade foi passado
if (isset($_GET['city'])) {
    $city = $_GET['city'];

    // Cria um cliente Guzzle
    $client = new Client([
        'verify' => false, // Desabilita a verificação SSL (apenas para testes)
    ]);

    try {
        // Faz a requisição para a API do OpenWeatherMap
        $response = $client->request('GET', "https://api.openweathermap.org/data/2.5/weather", [
            'query' => [
                'q' => $city,
                'appid' => $apiKey,
                'units' => 'metric' // Para obter a temperatura em Celsius
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        // Exibe os dados do clima
        if ($data['cod'] == 200) {
            $responseData = [
                'city' => $data['name'],
                'temperature' => $data['main']['temp'],
                'description' => $data['weather'][0]['description'],
                'humidity' => $data['main']['humidity'], // Umidade
                'wind_speed' => $data['wind']['speed'], // Velocidade do vento
            ];
        } else {
            $responseData = ['error' => 'Cidade não encontrada.'];
        }
    } catch (\GuzzleHttp\Exception\RequestException $e) {
        $responseData = ['error' => 'Erro ao acessar a API: ' . $e->getMessage()];
    } catch (Exception $e) {
        $responseData = ['error' => 'Erro: ' . $e->getMessage()];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>API do Clima</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="card mx-auto" style="max-width: 600px;">
            <div class="card-body">
                <h1 class="card-title text-center mb-4">Consultar Clima</h1>
                <form action="index.php" method="get">
                    <div class="form-group">
                        <label for="city">Selecione uma Capital do Brasil:</label>
                        <select class="form-control" id="city" name="city" required>
                            <option value="">-- Selecione uma Capital --</option>
                            <option value="Rio de Janeiro">Rio de Janeiro</option>
                            <option value="São Paulo">São Paulo</option>
                            <option value="Brasília">Brasília</option>
                            <option value="Salvador">Salvador</option>
                            <option value="Fortaleza">Fortaleza</option>
                            <option value="Belo Horizonte">Belo Horizonte</option>
                            <option value="Manaus">Manaus</option>
                            <option value="Curitiba">Curitiba</option>
                            <option value="Recife">Recife</option>
                            <option value="Porto Alegre">Porto Alegre</option>
                            <option value="Belém">Belém</option>
                            <option value="São Luís">São Luís</option>
                            <option value="Teresina">Teresina</option>
                            <option value="Natal">Natal</option>
                            <option value="João Pessoa">João Pessoa</option>
                            <option value="Maceió">Maceió</option>
                            <option value="Aracaju">Aracaju</option>
                            <option value="Campo Grande">Campo Grande</option>
                            <option value="Cuiabá">Cuiabá</option>
                            <option value="Goiânia">Goiânia</option>
                            <option value="Palmas">Palmas</option>
                            <option value="Rio Branco">Rio Branco</option>
                            <option value="Boa Vista">Boa Vista</option>
                            <option value="Macapá">Macapá</option>
                            <option value="Porto Velho">Porto Velho</option>
                            <option value="Florianópolis">Florianópolis</option>
                            <option value="Vitória">Vitória</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Buscar</button>
                </form>

                <?php if (!empty($responseData)): ?>
                    <div class="mt-4">
                        <h2 class="text-center">Resultado:</h2>
                        <ul class="list-group">
                            <?php if (isset($responseData['error'])): ?>
                                <li class="list-group-item text-danger"><?php echo htmlspecialchars($responseData['error']); ?></li>
                            <?php else: ?>
                                <li class="list-group-item"><strong>Cidade:</strong> <?php echo htmlspecialchars($responseData['city']); ?></li>
                                <li class="list-group-item"><strong>Temperatura:</strong> <?php echo htmlspecialchars($responseData['temperature']); ?> °C</li>
                                <li class="list-group-item"><strong>Descrição:</strong> <?php echo htmlspecialchars($responseData['description']); ?></li>
                                <li class="list-group-item"><strong>Umidade:</strong> <?php echo htmlspecialchars($responseData['humidity']); ?> %</li>
                                <li class="list-group-item"><strong>Velocidade do Vento:</strong> <?php echo htmlspecialchars($responseData['wind_speed']); ?> m/s</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>