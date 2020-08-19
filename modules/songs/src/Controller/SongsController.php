<?php 
namespace Drupal\songs\Controller;

use Drupal\Songs\SongService;
use Drupal\Core\Template\TwigEnvironment;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;

class SongsController extends ControllerBase implements ContainerInjectionInterface 
{
  /**
  * @var Drupal\Core\Template\TwigEnvironment
  */
  protected $twig;

  /**
   * 
   */
  protected $songService;

  public function __construct(TwigEnvironment $twig, SongService $songService)
  {
      $this->twig = $twig;
      $this->songService = $songService;
  }

  public static function create(ContainerInterface $container)
  {
      return new static(
          $container->get('twig'),
          $container->get('songs.song')
      );
  }

  /**
   * index
   * @param  string $name
   * @return string
   */
  public function index($name = 'hello') 
  {

    $template = $this->twig->loadTemplate(
      drupal_get_path('module', 'songs') . '/templates/home.html.twig'
    );

    //return val?

    $build = [
      'description' => [
        '#type' => 'inline_template',
        '#template' => file_get_contents($template),
      ]
      ];

    return $build;

  }

  public function admin()
  {

      $songs = $this->songService->read();

      $form = \Drupal::formBuilder()->getForm('Drupal\songs\Form\SongsForm');

      $form['field']['value'] = 'From controller';
      $twig = \Drupal::service('twig');

      $template = 'tunes-admin-form';

      $template = $twig->loadTemplate(
          drupal_get_path('module', 'songs') . '/templates/'.$template.'.html.twig'
      );

      $build = [
            'description' => [
            '#type' => 'inline_template',
            '#template' => file_get_contents($template),
            '#context' => [
                'form' => $form,
                'songs' => $songs,
            ]
          ]
      ];

      return $build;
  
  }

  public function upload() 
  {
      dd($_FILES);
  }

  public function delete($id)
  {
      //delete record in db


      //delete file
      $resp = $this->songService->delete($id);

      //need a return value? yes, might need to be a redirect

      return $resp;
  }

}