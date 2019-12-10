<?php

declare(strict_types=1);

namespace Fronty\SyliusNetaxeptPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Ondrej Seliga <ondrej@seliga.cz>
 */
final class SyliusNetaxeptConfigurationType extends AbstractType
{
	/**
     * {@inheritdoc}
     */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('environment', ChoiceType::class, [
                'choices' => [
                    'fronty.netaxept.sandbox' => 'sandbox',
                    'fronty.netaxept.production' => 'production'
                ],
                'label' => 'fronty.netaxept.environment',
            ])
			->add('merchantId', TextType::class, [
				'label' => 'fronty.netaxept.merchant_id',
				'constraints' => [
					new NotBlank([
						'message' => 'fronty.netaxept.configuration.merchant_id.not_blank',
						'groups' => ['sylius']
					])
				]
			])
			->add('serviceId', TextType::class, [
				'label' => 'fronty.netaxept.service_id',
				'constraints' => [
					new NotBlank([
						'message' => 'fronty.netaxept.configuration.service_id.not_blank',
						'groups' => ['sylius']
					])
				]
			])
			->add('serviceKey', TextType::class, [
				'label' => 'fronty.netaxept.service_key',
				'constraints' => [
					new NotBlank([
						'message' => 'fronty.netaxept.configuration.service_key.not_blank',
						'groups' => ['sylius']
					])
				]
			]);
	}
}