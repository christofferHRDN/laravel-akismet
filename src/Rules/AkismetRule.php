<?php

namespace nickurt\Akismet\Rules;

use Illuminate\Contracts\Validation\Rule;

class AkismetRule implements Rule
{
    /**
     * @var
     */
    protected $email;

    /**
     * @var
     */
    protected $author;

    /**
     * @var
     */
    protected $content;

    /**
     * @var
     */
    protected $type;

    /**
     * Create a new rule instance.
     *
     * @param $email
     * @param $author
     *
     * @return void
     */
    public function __construct($email, $author, $content = null, $type = 'registration')
    {
        $this->email = $email;
        $this->author = $author;
        $this->content = $content;
        $this->type = $type;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $akismet = akismet();

        if ($akismet->validateKey()) {
            $akismet->setCommentAuthor($this->author)
                ->setCommentAuthorEmail($this->email)
                ->setCommentContent($this->content)
                ->setCommentType($this->type);

            return $akismet->isSpam() ? false : true;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('akismet::akismet.' . $this->type);
    }
}
