<?php
    require("./vendor/autoload.php");

    use Jose\Component\Core\AlgorithmManager;
    use Jose\Component\Encryption\Algorithm\KeyEncryption\RSAOAEP256;
    use Jose\Component\Encryption\Algorithm\ContentEncryption\A128GCM;
    use Jose\Component\Encryption\Compression\CompressionMethodManager;
    use Jose\Component\Encryption\Compression\Deflate;
    use Jose\Component\Encryption\JWEBuilder;
    use Jose\Component\KeyManagement\JWKFactory;
    use Jose\Component\Encryption\Serializer\CompactSerializer;
    
    // Key encryption algorithm manager - RSA0AEP256 algorithm.
    $keyEncryptionAlgorithmManager = new AlgorithmManager([
        new RSAOAEP256(),
    ]);
    
    // COntent encryption algorithm manager - A128GCM algorithm.
    $contentEncryptionAlgorithmManager = new AlgorithmManager([
        new A128GCM(),
    ]);
    
    // Compression method manager - DEF (Deflate) method.
    $compressionMethodManager = new CompressionMethodManager([
        new Deflate(),
    ]);
    
    // Instantiate JWE Builder.
    $jweBuilder = new JWEBuilder(
        $keyEncryptionAlgorithmManager,
        $contentEncryptionAlgorithmManager,
        $compressionMethodManager
    );
    
    // Configure key - ensure publick key formatting is correct
    $publicKey = 'enter here';

    $key = JWKFactory::createFromKey($publicKey,
        'Secret',
        [
            'kid' => 'enter here'
        ]
    );
    
    // Construct payload (add additional paramaters as required)
    $payload = json_encode([
        'sub' => ''
    ], JSON_UNESCAPED_SLASHES);

    // Combine token content
    $jwe = $jweBuilder
        ->create()
        ->withPayload($payload)
        ->withSharedProtectedHeader([
            'alg' => 'RSA-OAEP-256',
            'typ' => 'JWT',
            'zip' => 'DEF',
            'enc' => 'A128GCM',
            'kid' => 'enter here'
        ])
        ->addRecipient($key)
        ->build();

    // Serialize into token format
    $serializer = new CompactSerializer();

    $wat = $serializer->serialize($jwe, 0);

    print_r($wat);
?>