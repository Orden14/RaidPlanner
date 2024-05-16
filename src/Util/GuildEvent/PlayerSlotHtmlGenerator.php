<?php

namespace App\Util\GuildEvent;

use App\Entity\Build;
use App\Entity\GuildEventRelation\PlayerSlot;
use App\Entity\User;

final readonly class PlayerSlotHtmlGenerator
{
    public function generateTakenSlotHtml(User $user, PlayerSlot $playerSlot): string
    {
        $userDiv = $this->getUserDivHtml($user, $playerSlot);
        $buildDiv = $this->getBuildDivHtml($playerSlot->getBuild());

        return "$userDiv $buildDiv";
    }

    private function getUserDivHtml(User $user, PlayerSlot $playerSlot): string
    {
        return "
            <div class='col ge-vertical-center'>
                <a class='link-danger text-decoration-none ge-slot-icon me-2 cursor-pointer' href='/guild-event/player-slot/slot/free/{$playerSlot->getId()}' data-controller='guild-event--confirm-free-slot' data-current-user='{$user->getUsername()}' data-slot-user='{$playerSlot->getPlayer()?->getUsername()}'>
                    <i class='bi bi-x-lg ps-2' title='Libérer le slot'></i>
                </a>
                
                {$this->getTankIconHtml($playerSlot)}
                
                
                <span class='me-3 ge-text '>
                    <img class='small-icon rounded-circle' src='/profile_picture/{$playerSlot->getPlayer()?->getProfilePicture()}' alt='Profile'>
                    {$playerSlot->getPlayer()?->getUsername()}
                </span>
        
        </div>
        ";
    }

    private function getBuildDivHtml(Build $build): string
    {
        $specialization = $build->getSpecialization();

        return "
            <div class='col-auto ge-md-vertical-center'>
                                                        
                <span class='pe-md-2'>
                    <a href='/build/{$build->getId()}'
                        title='{$build->getName()}'
                        class='text-decoration-none ge-secondary-text'
                        style='color: {$specialization?->getJob()?->getColor()}'
                    >
                        <img src='/icon/{$specialization?->getIcon()}' alt='{$specialization?->getName()} icon' title='{$specialization?->getName()}' class='small-icon ge-secondary-img'>
                        {$build->getName()}
                    </a>
                </span>
                
                <span>
                    {$this->getBuildCategoryIconsHtml($build)}
                </span>
            </div>
        ";
    }

    private function getBuildCategoryIconsHtml(Build $build): string
    {
        $categoryIconsHtml = '';

        foreach ($build->getCategories() as $type) {
            $categoryIconsHtml .= "<img src='/icon/{$type->getIcon()}' alt='{$type->getName()} icon' title='{$type->getName()}' class='small-icon ge-secondary-img'>";
        }

        return $categoryIconsHtml;
    }

    private function getTankIconHtml(PlayerSlot $playerSlot): string
    {
        if ($playerSlot->isTank()) {
            return "<span class='me-1'>
                <img src='/guild_event/tank.png' alt='Tank icon' title='Tank' class='small-icon'>
            </span>";
        }

        return '';
    }
}
