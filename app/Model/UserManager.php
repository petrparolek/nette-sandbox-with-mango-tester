<?php declare(strict_types = 1);

namespace App\Model;

use Nette;
use Nette\Security\Passwords;

/**
 * Users management.
 */
final class UserManager implements Nette\Security\Authenticator
{

	use Nette\SmartObject;

	private const TABLE_NAME = 'users';
	private const COLUMN_ID = 'id';
	private const COLUMN_NAME = 'username';
	private const COLUMN_PASSWORD_HASH = 'password';
	private const COLUMN_EMAIL = 'email';
	private const COLUMN_ROLE = 'role';

	private Nette\Database\Explorer $database;

	private Passwords $passwords;

	public function __construct(Nette\Database\Explorer $database, Passwords $passwords)
	{
		$this->database = $database;
		$this->passwords = $passwords;
	}

	/**
	 * Performs an authentication.
	 *
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(string $username, string $password): Nette\Security\IIdentity
	{
		$row = $this->database->table(self::TABLE_NAME)
			->where(self::COLUMN_NAME, $username)
			->fetch();

		if (!$row instanceof Nette\Database\Table\ActiveRow) {
			throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IdentityNotFound);
		} elseif (!$this->passwords->verify($password, $row[self::COLUMN_PASSWORD_HASH])) {
			throw new Nette\Security\AuthenticationException('The password is incorrect.', self::InvalidCredential);
		} elseif ($this->passwords->needsRehash($row[self::COLUMN_PASSWORD_HASH])) {
			$row->update([
				self::COLUMN_PASSWORD_HASH => $this->passwords->hash($password),
			]);
		}

		$arr = $row->toArray();
		unset($arr[self::COLUMN_PASSWORD_HASH]);

		return new Nette\Security\SimpleIdentity($row[self::COLUMN_ID], $row[self::COLUMN_ROLE], $arr);
	}

	/**
	 * Adds new user.
	 *
	 * @throws DuplicateNameException
	 */
	public function add(string $username, string $email, string $password): void
	{
		Nette\Utils\Validators::assert($email, 'email');
		try {
			$this->database->table(self::TABLE_NAME)->insert([
				self::COLUMN_NAME => $username,
				self::COLUMN_PASSWORD_HASH => $this->passwords->hash($password),
				self::COLUMN_EMAIL => $email,
			]);
		} catch (Nette\Database\UniqueConstraintViolationException $e) {
			throw new DuplicateNameException();
		}
	}

}
