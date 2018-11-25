<?php

namespace Zestfule\UserBundle\Controller;

use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Zestfule\UserBundle\Form\RegisterType;
use Zestfule\UserBundle\Form\ResetPasswordRequestType;
use Zestfule\UserBundle\Form\ResetPasswordType;
use Zestfule\UserBundle\Model\ProfileInterface;
use Zestfule\UserBundle\Model\UserInterface;

/**
 * Class SecurityController of user
 * @package Zestfule\UserBundle\Controller
 * @license MIT
 * @author Erling Thorkildsen <erling.thorkildsen@interestfule.com>
 * @link https://gitlab.com/zestfule/user
 *
 * @Route(
 *     "/security",
 *     name="security_"
 * )
 */
class SecurityController extends Controller
{
    /**
     * Login
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(
     *     "/login",
     *     name="login"
     * )
     *
     * @Security(
     *     "!is_granted('IS_AUTHENTICATED_FULLY') and !is_granted('IS_AUTHENTICATED_REMEMBERED')"
     * )
     */
    public function login()
    {
        $auth = $this->get('security.authentication_utils');
        return $this->render($this->getParameter('zestfule_user.template_path').'/Login/login_full.html.twig', [
            'last_username' => $auth->getLastUsername(),
            'error' => $auth->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route(
     *     "/logout",
     *     name="logout"
     * )
     */
    public function logout()
    {
    }

    /**
     * Register
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     *
     * @Route(
     *     "/register",
     *     name="register"
     * )
     *
     * @Security(
     *     "!is_granted('IS_AUTHENTICATED_FULLY') and !is_granted('IS_AUTHENTICATED_REMEMBERED')"
     * )
     */
    public function register(Request $request)
    {
        // Verify registration is enabled,
        if (!$this->getParameter('zestfule_user.user_registration'))
        {
            $this->addFlash('error', $this->get('translator')->trans('security_messages.registration_disable'));

            return $this->redirectToRoute('security_login');
        }

        // Build Form.
        $user = $this->getParameter('zestfule_user.user_class');
        $user = new $user();
        if (!$user instanceof UserInterface) {
            throw new InvalidArgumentException();
        }

        $form = $this->createForm(RegisterType::class, $user, [
            'data_class' => $this->getParameter('zestfule_user.user_class'),
        ]);

        // Handle submit.
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            // Encode password.
            $encoder = $this->get('security.password_encoder');
            $password = $encoder->encodePassword($user, $form->get('plainPassword')->getData());
            $user->setPassword($password);

            // Check if Email Verification is enabled.
            if ($this->getParameter('zestfule_user.email_confirmation'))
            {
                // Set user as disabled.
                $user->setEnabled(false);

                // Check for confirmation token, generate one if not.
                if (empty($user->getConfirmationToken()) || $user->getConfirmationToken() === null)
                {
                    // TODO: Catch exception for duplicate?
                    $user->createConfirmationToken();
                }

                // Send verification email.
                // TODO: Translations
                $message = $this->generateUrl(
                    'security_register_confirm',
                    ['token' => $user->getConfirmationToken()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                );
                $email = (new \Swift_Message('Registration'))
                    ->setFrom('example@example.com')
                    ->setTo($user->getEmail())
                    ->setBody($message);
                $this->get('mailer')->send($email);
            } else {
                // If not required just send welcome email if enabled.
                if ($this->getParameter('zestfule_user.email_welcome'))
                {
                    // TODO: Translations
                    $email = (new \Swift_Message('Welcome'))
                        ->setFrom('example@example.com')
                        ->setTo($user->getEmail())
                        ->setBody('Welcome!');
                    $this->get('mailer')->send($email);
                }
            }

            // TODO: Default user group?
            // Give user UUID4.
            $user_uuid4 = Uuid::uuid4();
            $user->setId($user_uuid4);

            $user->setCreatedAt(new \DateTime('now', new \DateTimeZone('UTC')));

            // Create empty profile.
            $profile = $this->getParameter('zestfule_user.profile_class');
            $profile = new $profile();
            if (!$profile instanceof ProfileInterface)
            {
                throw new InvalidArgumentException();
            }

            // Give profile UUID4
            $profile_uuid4 = Uuid::uuid4();
            $profile->setId($profile_uuid4);

            // Attach them.
            $user->setProfile($profile);

            // Save the new user/profile.
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->persist($profile);
            $this->getDoctrine()->getManager()->flush();

            // Render success
            return $this->render($this->getParameter('zestfule_user.template_path').'/Registration/registerSuccess.html.twig', [
                'user' => $user,
            ]);
        }

        return $this->render($this->getParameter('zestfule_user.template_path').'/Registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Confirm Registration
     *
     * @param string $token
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(
     *     "/register/confirm",
     *     name="register_confirm"
     * )
     *
     * @Security(
     *     "!is_granted('IS_AUTHENTICATED_FULLY') and !is_granted('IS_AUTHENTICATED_REMEMBERED')"
     * )
     */
    public function registerConfirm(string $token)
    {
        // Is there a user with this confirmation token?
        $user = $this->getDoctrine()->getManager()->getRepository($this->getParameter('zestfule_user.user_class'))->findOneBy(['confirmationToken' => $token]);
        if ($user === null)
        {
            throw $this->createNotFoundException(sprintf($this->get('translator')->trans('security_messages.token_not_found'), $token));
        }

        // Enable them!
        $user->setConfirmationToken(null);
        $user->setEnabled(true);

        // If enabled, send welcome email.
        if ($this->getParameter('zestfule_user.welcome_email'))
        {
            // TODO: Translations
            $email = (new \Swift_Message('Welcome'))
                ->setFrom('example@example.com')
                ->setTo($user->getEmail())
                ->setBody('Welcome!');
            $this->get('mailer')->send($email);
        }

        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        return $this->render($this->getParameter('zestfule_user.template_path').'/Registration/registerSuccess.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * Request Password Reset
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(
     *     "password/reset/request",
     *     name="password_reset_request"
     * )
     *
     * @Security(
     *     "!is_granted('IS_AUTHENTICATED_FULLY') and !is_granted('IS_AUTHENTICATED_REMEMBERED')"
     * )
     */
    public function resetPasswordRequest(Request $request)
    {
        // Build Form.
        $form = $this->createForm(ResetPasswordRequestType::class);

        // Handle Request.
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            // Check for user.
            $user = $this->getDoctrine()->getManager()->getRepository($this->getParameter('zestfule_user.user_class'))->findOneBy(['email' => $form->get('email')->getData()]);
            if ($user === null)
            {
                $form->get('email')->addError(new FormError($this->get('translator')->trans('security_validators.reset_email_not_found')));
            } else {
                // Check Password Reset Lifetime.
                if ($user->isPasswordRequestNotExpired($this->getParameter('zestfule_user.password_request_time')))
                {
                    // TODO: Display actual time for next password reset.
                    $form->get('email')->addError(new FormError($this->get('translator')->trans('security_message.reset_wait_before_resending')));
                } else {
                    // Create Password Reset Token.
                    if (empty($user->getPasswordResetToken()) || $user->getPasswordResetToken === null)
                    {
                        $user->createPasswordResetToken();
                        $user->setPasswordRequestedAt(new \DateTime('now', new \DateTimeZone('UTC')));
                    }

                    // Send Email.
                    // TODO: Translations
                    $email = (new \Swift_Message('Password Reset'))
                        ->setFrom('example@example.com')
                        ->setTo($user->getEmail())
                        ->setBody(
                            $this->generateUrl(
                                'security_password_reset',
                                ['token' => $user->getPasswordResetToken()],
                                UrlGeneratorInterface::ABSOLUTE_URL
                            )
                        );
                    $this->get('mailer')->send($email);

                    // Update User.
                    $this->getDoctrine()->getManager()->persist($user);
                    $this->getDoctrine()->getManager()->flush();

                    // Render request success.
                    return $this->render($this->getParameter('zestfule_user.template_path').'/PasswordReset/requestSuccess.html.twig', [
                        'sendEmail' => true,
                    ]);
                }
            }
        }

        // Render Request Form.
        return $this->render($this->getParameter('zestfule_user.template_path').'/PasswordReset/request.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Password Reset Form
     *
     * @param Request $request
     * @param string $token
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(
     *     "password/reset/{token}",
     *     name="password_reset"
     * )
     *
     * @Security(
     *     "!is_granted('IS_AUTHENTICATED_FULLY') and !is_granted('IS_AUTHENTICATED_REMEMBERED')"
     * )
     */
    public function resetPassword(Request $request, string $token)
    {
        // Is there a user with this reset token?
        $user = $this->getDoctrine()->getManager()->getRepository($this->getParameter('zestfule_user.user_class'))->findOneBy(['passwordResetToken' => $token]);
        if ($user === null)
        {
            throw $this->createNotFoundException(sprintf($this->get('translator')->trans('security_messages.token_not_found'), $token));
        }

        // Build form.
        $form = $this->createForm(ResetPasswordType::class, $user);

        // Handle submit.
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            // Encode and set password.
            $encoder = $this->get('security.password_encoder');
            $password = $encoder->encodePassword($user, $form->get('plainPassword')->getData());
            $user->setPassword($password)
                ->setPasswordResetToken(null)
                ->setPasswordRequestedAt(null);

            // Save user.
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            // Send email.
            // TODO: Translations
            $email = (new \Swift_Message('Password Reset Success'))
                ->setFrom('example@example.com')
                ->setTo($user->getEmail())
                ->setBody('Password reset.');
            $this->get('mailer')->send($email);

            // Render success.
            return $this->render($this->getParameter('zestfule_user.template_path').'/PasswordReset/requestSuccess.html.twig', [
                'sendEmail' => false,
            ]);
        }

        // Render form.
        return $this->render($this->getParameter('zestfule_user.template_path').'/PasswordReset/resetPassword.html.twig', [
            'token' => $token,
            'form' => $form->createView(),
        ]);
    }
}