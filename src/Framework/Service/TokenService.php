<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

namespace Ares\Framework\Service;

use Carbon\Carbon;
use InvalidArgumentException;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;
use UnexpectedValueException;

/**
 * Class TokenService
 *
 * @package Ares\Framework\Service
 */
class TokenService
{
    /**
     * @var string The issuer name
     */
    private string $issuer;

    /**
     * @var int Max lifetime in seconds
     */
    private int $lifetime;

    /**
     * @var string The private key
     */
    private string $privateKey;

    /**
     * @var string The public key
     */
    private string $publicKey;

    /**
     * @var Sha256 The signer
     */
    private Sha256 $signer;

    /**
     * TokenService Constructor.
     *
     * @param   string  $issuer      The issuer name
     * @param   int     $lifetime    The max lifetime
     * @param   string  $privateKey  The private key as string
     * @param   string  $publicKey   The public key as string
     */
    public function __construct(
        string $issuer,
        int $lifetime,
        string $privateKey,
        string $publicKey
    ) {
        $this->issuer     = $issuer;
        $this->lifetime   = $lifetime;
        $this->privateKey = $privateKey;
        $this->publicKey  = $publicKey;
        $this->signer     = new Sha256();
    }

    /**
     * Get JWT max lifetime.
     *
     * @return int The lifetime in seconds
     */
    public function getLifetime(): int
    {
        return $this->lifetime;
    }

    /**
     * Create JSON web token.
     *
     * @param   array  $claims  The claims
     *
     * @return string The JWT
     * @throws UnexpectedValueException
     *
     */
    public function createJwt(array $claims): string
    {
        $issuedAt = Carbon::now()->getTimestamp();

        $builder = (new Builder())->issuedBy($this->issuer)
            ->identifiedBy(uuid_create(), true)
            ->issuedAt($issuedAt)
            ->canOnlyBeUsedAfter($issuedAt)
            ->expiresAt($issuedAt + $this->lifetime);

        foreach ($claims as $name => $value) {
            $builder = $builder->withClaim($name, $value);
        }

        return $builder->getToken($this->signer,
            new Key('file://' . base_dir() . $this->privateKey)
        );
    }

    /**
     * Parse token.
     *
     * @param   string  $token  The JWT
     *
     * @return Token The parsed token
     * @throws InvalidArgumentException
     *
     */
    public function createParsedToken(string $token): Token
    {
        return (new Parser())->parse($token);
    }

    /**
     * Validate the access token.
     *
     * @param   string  $accessToken  The JWT
     *
     * @return bool The status
     */
    public function validateToken(string $accessToken): bool
    {
        $token = $this->createParsedToken($accessToken);

        if (!$token->verify($this->signer, 'file://' . base_dir() . $this->publicKey)) {
            // Token signature is not valid
            return false;
        }

        // Check whether the token has not expired
        $data = new ValidationData();
        $data->setCurrentTime(Carbon::now()->getTimestamp());
        $data->setIssuer($token->getClaim('iss'));
        $data->setId($token->getClaim('jti'));

        return $token->validate($data);
    }
}