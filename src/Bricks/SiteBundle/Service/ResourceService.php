<?php

namespace Bricks\SiteBundle\Service;

class ResourceService
{
    protected $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    /************************************************************************************************************
     * Repository functions
     ************************************************************************************************************/

    /**
     * search Resources
     *
     * $params = array(
     *      'disable_brick'             => if true, don't search in Bricks
     *      'disable_external_resource' => if true, don't search in ExternalResources
     *      'published' => if true, search only ublished resources
     *      'q' => string, search $params['q'] in resource's title
     *      'tag_name' => string, search tags with name = $options['tag_name']
     *      'max_results' => is set and integer, limits the numer of results
     * )
     *
     * @param array $params array of parameters
     * @return multitype:
     */
    public function search(array $options = array())
    {
        $options = array_merge(array(
            'disable_brick' => false,
            'disable_external_resource' => false,
            'q' => null,
            'published' => true,
            'tag_name' => null,
            'max_results' => null
        ), $options);

        /**
         * fetch Bricks
         */
        if ($options['disable_brick'] == false) {
            $qb = $this->em->createQueryBuilder()
                ->select('e')
                ->from('BricksSiteBundle:Brick', 'e')
                ->where('e.published = :published')
                ->orderBy('e.publishedAt', 'DESC')
            ;

            /**
             * $options['published']
             */
            $qb->andWhere('e.published = :published')
                ->setParameter('published', $options['published'])
            ;

            /**
             * $options['q']
             */
            if ($options['q'] != null) {
                $qb->andWhere($qb->expr()->like('e.title', ':q'))
                    ->setParameter('q', '%'.$options['q'].'%')
                ;
            }

            /**
             * $options['tag_slug'] filter
             *
             * search in Tag.slug field
             */
            if ($options['tag_name'] != null) {
                // find all brick ids matching a particular query
                $tagRepo = $this->em->getRepository('BricksSiteBundle:Tag');
                $ids = $tagRepo->getResourceIdsForTag('brick_tag', $options['tag_name']);

                if (count($ids) > 0) {
                    $qb->andWhere($qb->expr()->in('e.id', $ids));
                } else {
                    $qb->andWhere('e.id IS NULL');
                }
            }

            /**
             * $options['max_results']
             */
            if (is_integer($options['max_results'])) {
                $qb->setMaxResults($options['max_results']);
            }

            $bricks = $qb->getQuery()->getResult();
        } else {
            $bricks = array();
        }

        /**
         * fetch ExternalResources
         */
        if ($options['disable_external_resource'] == false) {
            $qb = $this->em->createQueryBuilder()
                ->select('e')
                ->from('BricksSiteBundle:ExternalResource', 'e')
                ->setParameter('published', true)
                ->orderBy('e.publishedAt', 'DESC')
            ;

            /**
             * $options['published']
             */
            $qb->andWhere('e.published = :published')
                ->setParameter('published', $options['published'])
            ;

            /**
             * $options['q']
             */
            if ($options['q'] != null) {
                $qb->andWhere($qb->expr()->like('e.title', ':q'))
                    ->setParameter('q', '%'.$options['q'].'%')
                ;
            }

            /**
             * $options['tag_slug'] filter
             *
             * search in Tag.slug field
             */
            if ($options['tag_name'] != null) {
                // find all brick ids matching a particular query
                $tagRepo = $this->em->getRepository('BricksSiteBundle:Tag');
                $ids = $tagRepo->getResourceIdsForTag('external_resource_tag', $options['tag_name']);

                if (count($ids) > 0) {
                    $qb->andWhere($qb->expr()->in('e.id', $ids));
                } else {
                    $qb->andWhere('e.id IS NULL');
                }
            }

            /**
             * $options['max_results']
             */
            if (is_integer($options['max_results'])) {
                $qb->setMaxResults($options['max_results']);
            }

            $externalResources = $qb->getQuery()->getResult();
        } else {
            $externalResources = array();
        }

        $output = array_merge($bricks, $externalResources);

        uasort($output, function ($a, $b) {
            return $a->getPublishedAt() <= $b->getPublishedAt();
        });

        /**
         * $options['max_results']
         *
         * process again the $options['max_results'] parameter
         */
        if (is_integer($options['max_results'])) {
            $output = array_slice($output, 0, $options['max_results']);
        }

        return $output;
    }
} 