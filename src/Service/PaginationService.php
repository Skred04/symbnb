<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

class PaginationService {
    private $entityClass;
    private int $limit = 10;
    private int $page = 1;
    private EntityManagerInterface $manager;
    private Environment $twig;
    private $route;
    private $templatePath;

    public function __construct(EntityManagerInterface $manager, Environment $twig, RequestStack $requestStack, $templatePath) {
        $this->manager = $manager;
        $this->twig = $twig;
        $this->route = $requestStack->getCurrentRequest()->attributes->get("_route");
        $this->templatePath = $templatePath;
    }

    public function display() {
        $this->twig->display($this->templatePath, [
            "pages" => $this->getPages(),
            "page" => $this->page,
            "route" => $this->getRoute()
        ]);
    }

    public function getPages() {
        if (empty($this->entityClass)) {
            throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer ! Utilisez la méthode setEntityClass de votre objet PaginationService.");
        }
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());
        return ceil($total / $this->limit);
    }

    public function getData() {
        if (empty($this->entityClass)) {
            throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer ! Utilisez la méthode setEntityClass de votre objet PaginationService.");
        }
        $offset = $this->limit * $this->page - $this->limit;
        $repo = $this->manager->getRepository($this->entityClass);

        return $repo->findBy([], [], $this->limit, $offset);
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page): PaginationService
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit(int $limit): PaginationService
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * @param mixed $entityClass
     */
    public function setEntityClass($entityClass): PaginationService
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param mixed $route
     */
    public function setRoute($route): PaginationService
    {
        $this->route = $route;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    /**
     * @param mixed $templatePath
     */
    public function setTemplatePath($templatePath): PaginationService
    {
        $this->templatePath = $templatePath;

        return $this;
    }
}