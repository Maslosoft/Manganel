<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganelTest\Models;

use Maslosoft\Mangan\Document;
use Maslosoft\Mangan\Model\Image;
use Maslosoft\Mangan\Sanitizers\DateSanitizer;
use MongoDB\BSON\UTCDateTime as MongoDate;

/**
 * Example model containing nested data, taken from other project
 *
 * @SearchIndex
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class NestedModel extends Document
{
	/**
	 * User statuses
	 */

	/**
	 * @deprecated Use enum UserStatus::* instead
	 */
	const STATUS_NOTACTIVE = 0;

	/**
	 * @deprecated Use enum UserStatus::* instead
	 */
	const STATUS_ACTIVE = 1;

	/**
	 * @deprecated Use enum UserStatus::* instead
	 */
	const STATUS_BANNED = -1;

	/**
	 * Groups to which user belongs
	 * @Safe
	 * @AllowSet('uac.user.usersAdmin')
	 * @Label('User groups')
	 * @var string[]
	 */
	public $groups = [
		'public' => true
	];

	/**
	 * Array with sso ID's
	 * @var mixed[]
	 */
	public $sso = [];

	/**
	 * @Label('Personal motto')
	 * @I18N(allowAny = true)
	 * @SafeValidator
	 * @FormRenderer('TextArea')
	 * @see TextArea
	 * @var string
	 */
	public $motto;

	/**
	 * @Label('About me')
	 * @I18N(allowAny = true)
	 * @SafeValidator
	 * @FormRenderer('HtmlArea')
	 * @see HtmlArea
	 * @var string
	 */
	public $about;

	/**
	 * @Label('Avatar')
	 * @Embedded(Image)
	 * @Search(false)
	 * @var Image
	 */
	public $avatar;

	/**
	 * Whether to use gravatar instead of uploaded image
	 *
	 * @Label('Use gravatar for profile photo')
	 * @Description('If you have account on gravatar, you could manage your profile photos at many websites at once. Visit https://gravatar.com for more details.')
	 * @Renderer('Toggle')
	 * @var bool
	 */
	public $gravatar = false;

	/**
	 * @Label('Avatar')
	 * @Renderer('Icon')
	 * @Persistent(false)
	 * @see Icon
	 * @var string
	 */
	public $avatarUrl;

	/**
	 * @Label('Username')
	 *
	 * @RequiredValidator
	 * @LengthValidator(min = 2, max = 20, message = @Label('Incorrect username (length between 3 and 20 characters)'))
	 * @MatchValidator(pattern = '~^[A-Za-z0-9_-]+$~', message = @Label('Incorrect symbols. Allowed: letters, numbers, underscore and hyphen.'))
	 * @UniqueValidator
	 *
	 * @var string
	 */
	public $username;

	/**
	 * @Label('First name')
	 * @SafeValidator
	 * @var string
	 */
	public $firstName = '';

	/**
	 * @Label('Last name')
	 * @SafeValidator
	 * @var string
	 */
	public $lastName = '';

	/**
	 * @Label('Full name')
	 * @ModelName
	 * @var string
	 */
	public $fullName = '';

	/**
	 * @Label('E-mail');
	 * @RequiredValidator
	 * @UniqueValidator
	 * @LengthValidator(min = 4, max = 128)
	 * @EmailValidator
	 * @var string
	 */
	public $email;

	/**
	 * @Label('Address')
	 * @Embedded('MAddress')
	 * @var MAddress
	 */
//	public $address;

	/**
	 * @Label('Password')
	 * @LengthValidator(min = 5, max = 128, message = @Label('Incorrect password (minimal length 4 symbols)'))
	 * @Description("Leave blank if you don't wanna change")
	 * @RequiredValidator(on = {'insert'})
	 * @SafeValidator
	 * @Search(false)
	 * @ToJson(false)
	 * @var string
	 */
	public $password;

	/**
	 * @Label('Activation key')
	 * @KoBindable(false)
	 * @Secret()
	 * @Search(false)
	 * @var string
	 */
	public $activationKey;

	/**
	 * @Label('Website admin')
	 * @deprecated since version number
	 * @var boolean
	 */
	public $superuser = false;

	/**
	 * @Label('Status')
	 * @Safe
	 * @var enum
	 */
	public $status = 0;

	/**
	 * @Label('Last seen')
	 * @Readonly
	 * @Sanitizer(DateSanitizer)
	 * @see TimeAgo
	 * @see DateSanitizer
	 * @var MongoDate
	 */
	public $lastSeen = null;

}
