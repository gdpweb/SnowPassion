<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Trick;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class CommentManager
{
    const NB_COMMENTS_PAGE = 5;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getComments(Trick $trick, $page = 1)
    {
        return $this->em->getRepository('AppBundle:Comment')->getComments(
            $trick->getId(),
            $page,
            self::NB_COMMENTS_PAGE
        );
    }

    public function getNbPages($listComments)
    {
        return ceil(count($listComments) / self::NB_COMMENTS_PAGE);
    }

    public function createComment(Comment $comment, Trick $trick, User $user)
    {
        $comment->setAuteur($user);
        $comment->setTrick($trick);
        $this->em->persist($comment);
        $this->em->flush();
    }
}
