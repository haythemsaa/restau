/**
 * AI Content Generator Screen
 */

import React, {useState} from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  ScrollView,
  ActivityIndicator,
  Clipboard,
} from 'react-native';
import LinearGradient from 'react-native-linear-gradient';
import Icon from 'react-native-vector-icons/Ionicons';
import {aiApi} from '../../services/api';
import {colors, typography, spacing, borderRadius, shadows} from '../../utils/theme';
import Toast from 'react-native-toast-message';

const AIGeneratorScreen = () => {
  const [selectedType, setSelectedType] = useState('facebook');
  const [prompt, setPrompt] = useState('');
  const [tone, setTone] = useState('professional');
  const [generatedContent, setGeneratedContent] = useState('');
  const [loading, setLoading] = useState(false);

  const contentTypes = [
    {id: 'facebook', label: 'Facebook', icon: 'logo-facebook', color: '#1877F2'},
    {id: 'instagram', label: 'Instagram', icon: 'logo-instagram', gradient: ['#F58529', '#DD2A7B']},
    {id: 'twitter', label: 'Twitter', icon: 'logo-twitter', color: '#1DA1F2'},
    {id: 'email', label: 'Email', icon: 'mail', color: colors.primary},
  ];

  const tones = [
    {id: 'professional', label: 'Professionnel', icon: 'briefcase'},
    {id: 'friendly', label: 'Amical', icon: 'happy'},
    {id: 'enthusiastic', label: 'Enthousiaste', icon: 'flash'},
    {id: 'casual', label: 'Décontracté', icon: 'chatbubbles'},
  ];

  const handleGenerate = async () => {
    if (!prompt.trim()) {
      Toast.show({
        type: 'error',
        text1: 'Erreur',
        text2: 'Veuillez entrer un sujet ou une description',
      });
      return;
    }

    setLoading(true);
    setGeneratedContent('');

    try {
      const response = await aiApi.generateContent({
        type: selectedType,
        prompt: prompt,
        tone: tone,
      });

      if (response.data && response.data.content) {
        setGeneratedContent(response.data.content);
        Toast.show({
          type: 'success',
          text1: 'Contenu généré!',
          text2: 'Votre contenu IA est prêt',
        });
      }
    } catch (error) {
      console.error('AI generation error:', error);

      // For demo, generate mock content
      const mockContent = generateMockContent(selectedType, prompt, tone);
      setGeneratedContent(mockContent);

      Toast.show({
        type: 'success',
        text1: 'Contenu généré!',
        text2: 'Mode démo - Contenu d\'exemple',
      });
    } finally {
      setLoading(false);
    }
  };

  const generateMockContent = (type, userPrompt, userTone) => {
    const contents = {
      facebook: `🍽️ Découvrez notre nouveau menu automne!\n\nNous sommes ravis de vous présenter nos plats de saison préparés avec des produits locaux et frais. 🍂\n\n✨ Entrée: Velouté de butternut aux noisettes\n🥘 Plat: Magret de canard aux figues\n🍰 Dessert: Tarte aux pommes maison\n\nRéservez dès maintenant: 01 23 45 67 89\n\n#RestaurantGourmet #CuisineLocale #MenuAutomne`,
      instagram: `🍽️ NOUVEAU MENU AUTOMNE 🍂\n\nLes saveurs de saison arrivent dans vos assiettes!\n\n📸 Swipe pour découvrir nos créations\n👨‍🍳 Chef passionné | Produits locaux\n📍 Paris 8ème\n📞 Réservations en bio\n\n#foodie #gastronomie #restaurant #paris #foodporn #instafood #frenchcuisine #automne #fall`,
      twitter: `🍂 Notre nouveau menu automne est arrivé!\n\nVelouté butternut, magret aux figues, tarte aux pommes... Des saveurs authentiques avec des produits locaux.\n\nRéservez: 01 23 45 67 89\n\n#Restaurant #Paris #Gastronomie`,
      email: `Objet: Découvrez notre nouveau menu automne 🍂\n\nBonjour,\n\nNous sommes heureux de vous annoncer l'arrivée de notre menu automne, composé de plats savoureux préparés avec des produits de saison.\n\nAu programme:\n• Velouté de butternut aux noisettes\n• Magret de canard aux figues\n• Tarte aux pommes maison\n\nRéservez votre table dès maintenant au 01 23 45 67 89 ou directement sur notre site.\n\nÀ très bientôt,\nL'équipe du restaurant`,
    };

    return contents[type] || 'Contenu généré avec succès!';
  };

  const handleCopy = () => {
    if (generatedContent) {
      Clipboard.setString(generatedContent);
      Toast.show({
        type: 'success',
        text1: 'Copié!',
        text2: 'Le contenu a été copié dans le presse-papiers',
      });
    }
  };

  const handleShare = () => {
    Toast.show({
      type: 'info',
      text1: 'Partage',
      text2: 'Fonctionnalité de partage à venir',
    });
  };

  return (
    <View style={styles.container}>
      {/* Header */}
      <LinearGradient
        colors={colors.gradient.primary}
        style={styles.header}
        start={{x: 0, y: 0}}
        end={{x: 1, y: 1}}>
        <Icon name="sparkles" size={40} color={colors.white} />
        <Text style={styles.headerTitle}>Générateur IA</Text>
        <Text style={styles.headerSubtitle}>
          Créez du contenu professionnel en quelques secondes
        </Text>
      </LinearGradient>

      <ScrollView
        style={styles.content}
        contentContainerStyle={styles.scrollContent}
        showsVerticalScrollIndicator={false}>
        {/* Content Type Selection */}
        <View style={styles.section}>
          <Text style={styles.sectionTitle}>Type de contenu</Text>
          <View style={styles.typeContainer}>
            {contentTypes.map(type => (
              <TouchableOpacity
                key={type.id}
                style={[
                  styles.typeButton,
                  selectedType === type.id && styles.typeButtonActive,
                ]}
                onPress={() => setSelectedType(type.id)}>
                <Icon
                  name={type.icon}
                  size={24}
                  color={selectedType === type.id ? colors.white : type.color || colors.primary}
                />
                <Text
                  style={[
                    styles.typeButtonText,
                    selectedType === type.id && styles.typeButtonTextActive,
                  ]}>
                  {type.label}
                </Text>
              </TouchableOpacity>
            ))}
          </View>
        </View>

        {/* Tone Selection */}
        <View style={styles.section}>
          <Text style={styles.sectionTitle}>Ton</Text>
          <View style={styles.toneContainer}>
            {tones.map(toneOption => (
              <TouchableOpacity
                key={toneOption.id}
                style={[
                  styles.toneChip,
                  tone === toneOption.id && styles.toneChipActive,
                ]}
                onPress={() => setTone(toneOption.id)}>
                <Icon
                  name={toneOption.icon}
                  size={16}
                  color={tone === toneOption.id ? colors.white : colors.primary}
                />
                <Text
                  style={[
                    styles.toneChipText,
                    tone === toneOption.id && styles.toneChipTextActive,
                  ]}>
                  {toneOption.label}
                </Text>
              </TouchableOpacity>
            ))}
          </View>
        </View>

        {/* Prompt Input */}
        <View style={styles.section}>
          <Text style={styles.sectionTitle}>Sujet ou description</Text>
          <View style={styles.promptContainer}>
            <TextInput
              style={styles.promptInput}
              placeholder="Ex: Nouveau menu automne avec produits locaux"
              placeholderTextColor={colors.grayLight}
              value={prompt}
              onChangeText={setPrompt}
              multiline
              numberOfLines={4}
              textAlignVertical="top"
            />
          </View>
        </View>

        {/* Generate Button */}
        <TouchableOpacity
          style={styles.generateButton}
          onPress={handleGenerate}
          disabled={loading}>
          <LinearGradient
            colors={colors.gradient.primary}
            style={styles.generateButtonGradient}
            start={{x: 0, y: 0}}
            end={{x: 1, y: 0}}>
            {loading ? (
              <>
                <ActivityIndicator color={colors.white} />
                <Text style={styles.generateButtonText}>Génération...</Text>
              </>
            ) : (
              <>
                <Icon name="sparkles" size={20} color={colors.white} />
                <Text style={styles.generateButtonText}>Générer avec l'IA</Text>
              </>
            )}
          </LinearGradient>
        </TouchableOpacity>

        {/* Generated Content */}
        {generatedContent ? (
          <View style={styles.section}>
            <View style={styles.resultHeader}>
              <Text style={styles.sectionTitle}>Contenu généré</Text>
              <View style={styles.resultActions}>
                <TouchableOpacity style={styles.actionIcon} onPress={handleCopy}>
                  <Icon name="copy-outline" size={20} color={colors.primary} />
                </TouchableOpacity>
                <TouchableOpacity style={styles.actionIcon} onPress={handleShare}>
                  <Icon name="share-outline" size={20} color={colors.primary} />
                </TouchableOpacity>
              </View>
            </View>

            <View style={styles.resultContainer}>
              <Text style={styles.resultText}>{generatedContent}</Text>
            </View>

            <View style={styles.resultInfo}>
              <Icon name="information-circle" size={16} color={colors.info} />
              <Text style={styles.resultInfoText}>
                Contenu généré par IA - Relisez avant publication
              </Text>
            </View>
          </View>
        ) : null}

        {/* Quick Tips */}
        <View style={styles.section}>
          <View style={styles.tipsCard}>
            <View style={styles.tipsHeader}>
              <Icon name="bulb" size={20} color={colors.warning} />
              <Text style={styles.tipsTitle}>Conseils</Text>
            </View>
            <View style={styles.tipsList}>
              <View style={styles.tipItem}>
                <Icon name="checkmark-circle" size={16} color={colors.success} />
                <Text style={styles.tipText}>
                  Soyez spécifique dans votre description
                </Text>
              </View>
              <View style={styles.tipItem}>
                <Icon name="checkmark-circle" size={16} color={colors.success} />
                <Text style={styles.tipText}>
                  Mentionnez les détails importants (dates, prix, etc.)
                </Text>
              </View>
              <View style={styles.tipItem}>
                <Icon name="checkmark-circle" size={16} color={colors.success} />
                <Text style={styles.tipText}>
                  Relisez et personnalisez le contenu généré
                </Text>
              </View>
            </View>
          </View>
        </View>

        <View style={styles.bottomPadding} />
      </ScrollView>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: colors.background,
  },
  header: {
    paddingTop: 60,
    paddingBottom: 30,
    paddingHorizontal: spacing.xl,
    alignItems: 'center',
    borderBottomLeftRadius: 30,
    borderBottomRightRadius: 30,
  },
  headerTitle: {
    fontSize: typography.fontSize['2xl'],
    fontWeight: 'bold',
    color: colors.white,
    marginTop: spacing.sm,
  },
  headerSubtitle: {
    fontSize: typography.fontSize.sm,
    color: colors.white,
    opacity: 0.9,
    marginTop: spacing.xs,
    textAlign: 'center',
  },
  content: {
    flex: 1,
  },
  scrollContent: {
    padding: spacing.base,
  },
  section: {
    marginBottom: spacing.xl,
  },
  sectionTitle: {
    fontSize: typography.fontSize.base,
    fontWeight: '600',
    color: colors.text,
    marginBottom: spacing.sm,
  },
  typeContainer: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    gap: spacing.sm,
  },
  typeButton: {
    flex: 1,
    minWidth: '45%',
    backgroundColor: colors.white,
    borderRadius: borderRadius.md,
    padding: spacing.base,
    alignItems: 'center',
    gap: spacing.xs,
    borderWidth: 2,
    borderColor: colors.border,
    ...shadows.sm,
  },
  typeButtonActive: {
    backgroundColor: colors.primary,
    borderColor: colors.primary,
  },
  typeButtonText: {
    fontSize: typography.fontSize.sm,
    fontWeight: '600',
    color: colors.text,
  },
  typeButtonTextActive: {
    color: colors.white,
  },
  toneContainer: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    gap: spacing.sm,
  },
  toneChip: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingHorizontal: spacing.base,
    paddingVertical: spacing.sm,
    borderRadius: borderRadius.full,
    backgroundColor: colors.white,
    borderWidth: 1,
    borderColor: colors.border,
    gap: 4,
  },
  toneChipActive: {
    backgroundColor: colors.primary,
    borderColor: colors.primary,
  },
  toneChipText: {
    fontSize: typography.fontSize.sm,
    fontWeight: '500',
    color: colors.text,
  },
  toneChipTextActive: {
    color: colors.white,
  },
  promptContainer: {
    backgroundColor: colors.white,
    borderRadius: borderRadius.md,
    borderWidth: 1,
    borderColor: colors.border,
    ...shadows.sm,
  },
  promptInput: {
    padding: spacing.base,
    fontSize: typography.fontSize.base,
    color: colors.text,
    minHeight: 100,
  },
  generateButton: {
    borderRadius: borderRadius.md,
    overflow: 'hidden',
    ...shadows.md,
    marginBottom: spacing.xl,
  },
  generateButtonGradient: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    paddingVertical: spacing.base,
    gap: spacing.sm,
  },
  generateButtonText: {
    fontSize: typography.fontSize.base,
    fontWeight: '600',
    color: colors.white,
  },
  resultHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: spacing.sm,
  },
  resultActions: {
    flexDirection: 'row',
    gap: spacing.sm,
  },
  actionIcon: {
    width: 36,
    height: 36,
    borderRadius: 18,
    backgroundColor: colors.backgroundLight,
    justifyContent: 'center',
    alignItems: 'center',
  },
  resultContainer: {
    backgroundColor: colors.white,
    borderRadius: borderRadius.md,
    padding: spacing.base,
    borderWidth: 1,
    borderColor: colors.border,
    ...shadows.sm,
  },
  resultText: {
    fontSize: typography.fontSize.base,
    color: colors.text,
    lineHeight: 24,
  },
  resultInfo: {
    flexDirection: 'row',
    alignItems: 'center',
    marginTop: spacing.sm,
    padding: spacing.sm,
    backgroundColor: `${colors.info}15`,
    borderRadius: borderRadius.sm,
    gap: spacing.xs,
  },
  resultInfoText: {
    flex: 1,
    fontSize: typography.fontSize.xs,
    color: colors.info,
  },
  tipsCard: {
    backgroundColor: colors.white,
    borderRadius: borderRadius.md,
    padding: spacing.base,
    ...shadows.sm,
  },
  tipsHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: spacing.sm,
    gap: spacing.xs,
  },
  tipsTitle: {
    fontSize: typography.fontSize.base,
    fontWeight: '600',
    color: colors.text,
  },
  tipsList: {
    gap: spacing.sm,
  },
  tipItem: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: spacing.xs,
  },
  tipText: {
    flex: 1,
    fontSize: typography.fontSize.sm,
    color: colors.textLight,
  },
  bottomPadding: {
    height: 20,
  },
});

export default AIGeneratorScreen;
